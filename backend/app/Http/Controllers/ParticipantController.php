<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use App\Models\Karya;
use App\Models\Peserta;
use App\Rules\ValidParticipantIdentity;
use App\Services\KaryaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class ParticipantController extends Controller
{
    /**
     * Daftarkan akun yang sedang login menjadi peserta lomba/festival atau PIC kampus resmi.
     */
    public function register(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Cek jika sudah terdaftar
        if ($user->peserta()->exists()) {
            return redirect()->route('dashboard', ['tab' => 'profil'])
                ->with('info', 'Profil kepesertaan Anda sudah terdaftar. Silakan perbarui melalui form Pembaruan Profil.');
        }

        $validated = $request->validate([
            'institusi'       => ['required', 'string', 'max:150'],
            'jenis_peserta'   => ['required', 'string', 'in:mahasiswa,siswa,siswa_smk,umum,pic'],
            'nomor_identitas' => [
                'required',
                'string',
                'max:30',
                new ValidParticipantIdentity(
                    $request->input('jenis_peserta'),
                    $request->input('institusi')
                ),
            ],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
        ], [
            'institusi.required'       => 'Asal kampus / institusi wajib diisi.',
            'jenis_peserta.required'   => 'Kategori peserta wajib dipilih.',
            'jenis_peserta.in'         => 'Kategori peserta tidak valid.',
            'nomor_identitas.required' => 'Nomor identitas (NIM / NIDN / NIP / NISN / KTP) wajib diisi.',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $phone = $validated['no_telepon'] ?? $user->person?->phone;

            Peserta::create([
                'user_id'         => $user->id,
                'nama_lengkap'    => $user->name,
                'email'           => $user->email,
                'no_telepon'      => $phone,
                'institusi'       => $validated['institusi'],
                'jenis_peserta'   => $validated['jenis_peserta'],
                'nomor_identitas' => $validated['nomor_identitas'],
            ]);

            // Selaraskan nomor telepon ke tabel people (profil akun)
            if (! empty($phone)) {
                $user->person()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name' => $user->name,
                        'email'     => $user->email,
                        'phone'     => $phone,
                    ]
                );
            }

            // Hanya konversi pengunjung ke tim-mahasiswa; PIC kampus tetap mempertahankan role pic-kampus
            if ($user->hasRole('pengunjung') && Role::where('name', 'tim-mahasiswa')->where('guard_name', 'web')->exists()) {
                $user->removeRole('pengunjung');
                $user->assignRole('tim-mahasiswa');
            }
        });

        $message = $user->hasRole('pic-kampus')
            ? 'Profil kampus berhasil diaktifkan. Anda sekarang dapat mendaftarkan tim dan hingga 2 karya inovasi institusi.'
            : 'Selamat! Profil kepesertaan Anda berhasil disimpan. Anda kini siap mengikuti kompetisi FUTURiSE.';

        return redirect()->route('dashboard')->with('status', $message);
    }

    /**
     * Perbarui profil kepesertaan / profil kampus milik pengguna yang sedang login.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $peserta = $user->peserta()->first();

        if (! $peserta) {
            return redirect()->route('dashboard', ['tab' => 'profil'])
                ->with('error', 'Profil kepesertaan belum tersedia. Silakan lengkapi pendaftaran terlebih dahulu.');
        }

        $validated = $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:120'],
            'institusi'       => ['required', 'string', 'max:150'],
            'nomor_identitas' => [
                'required',
                'string',
                'max:30',
                new ValidParticipantIdentity(
                    $peserta->jenis_peserta,
                    $request->input('institusi'),
                    $peserta->id
                ),
            ],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
        ], [
            'nama_lengkap.required'    => 'Nama lengkap PIC / Ketua Tim wajib diisi.',
            'institusi.required'       => 'Nama kampus / institusi wajib diisi.',
            'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
        ]);

        $oldInst = mb_strtolower(trim((string) $peserta->institusi));
        $newInst = mb_strtolower(trim($validated['institusi']));

        if ($oldInst !== $newInst && in_array($peserta->jenis_peserta, ['mahasiswa', 'siswa', 'siswa_smk', 'pic'], true)) {
            $activeFestival = Festival::aktif()->first();
            if ($activeFestival) {
                $ownActiveCount = Karya::query()
                    ->where('festival_id', $activeFestival->id)
                    ->where('peserta_id', $peserta->id)
                    ->where('status', '!=', 'tidak_lolos')
                    ->count();

                if ($ownActiveCount > 0) {
                    $targetActiveCount = Karya::query()
                        ->where('festival_id', $activeFestival->id)
                        ->where('status', '!=', 'tidak_lolos')
                        ->whereHas('submitter', function ($q) use ($newInst) {
                            $q->whereRaw('LOWER(TRIM(institusi)) = ?', [$newInst]);
                        })
                        ->count();

                    if (($targetActiveCount + $ownActiveCount) > KaryaService::MAX_WORKS_PER_INSTITUTION) {
                        throw ValidationException::withMessages([
                            'institusi' => ['Institusi tujuan sudah mencapai batas maksimal 2 karya usulan untuk festival aktif.'],
                        ]);
                    }
                }
            }
        }

        DB::transaction(function () use ($user, $peserta, $validated) {
            $phone = $validated['no_telepon'] ?? null;

            $peserta->update([
                'nama_lengkap'    => trim($validated['nama_lengkap']),
                'institusi'       => trim($validated['institusi']),
                'nomor_identitas' => $validated['nomor_identitas'],
                'no_telepon'      => $phone,
            ]);

            $user->update(['name' => trim($validated['nama_lengkap'])]);

            $user->person()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => trim($validated['nama_lengkap']),
                    'email'     => $user->email,
                    'phone'     => $phone,
                ]
            );
        });

        return redirect()->route('dashboard', ['tab' => 'profil'])
            ->with('status', 'Profil kampus dan kontak penanggung jawab berhasil diperbarui.');
    }
}
