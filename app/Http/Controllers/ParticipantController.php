<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ParticipantController extends Controller
{
    /**
     * Daftarkan akun yang sedang login menjadi peserta lomba/festival resmi.
     */
    public function register(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Cek jika sudah terdaftar
        if ($user->peserta) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah terdaftar sebagai peserta.');
        }

        $validated = $request->validate([
            'institusi'       => ['required', 'string', 'max:150'],
            'jenis_peserta'   => ['required', 'string', 'in:mahasiswa,siswa,umum,pic'],
            'nomor_identitas' => ['required', 'string', 'max:50'],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
        ], [
            'institusi.required'       => 'Asal kampus / institusi wajib diisi.',
            'jenis_peserta.required'   => 'Kategori peserta wajib dipilih.',
            'jenis_peserta.in'         => 'Kategori peserta tidak valid.',
            'nomor_identitas.required' => 'Nomor identitas (NIM / NISN / KTP) wajib diisi.',
        ]);

        DB::transaction(function () use ($user, $validated) {
            Peserta::create([
                'user_id'         => $user->id,
                'nama_lengkap'    => $user->name,
                'email'           => $user->email,
                'no_telepon'      => $validated['no_telepon'] ?? $user->person?->phone,
                'institusi'       => $validated['institusi'],
                'jenis_peserta'   => $validated['jenis_peserta'],
                'nomor_identitas' => $validated['nomor_identitas'],
            ]);

            // Jika role saat ini adalah pengunjung, ubah / tambahkan role tim-mahasiswa
            if ($user->hasRole('pengunjung') && Role::where('name', 'tim-mahasiswa')->where('guard_name', 'web')->exists()) {
                $user->removeRole('pengunjung');
                $user->assignRole('tim-mahasiswa');
            }
        });

        return redirect()->route('dashboard')->with('status', 'Selamat! Profil kepesertaan Anda berhasil disimpan. Anda kini siap mengikuti kompetisi FUTURiSE.');
    }
}
