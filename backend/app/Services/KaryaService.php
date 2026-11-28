<?php

namespace App\Services;

use App\Mail\CurationStatusMail;
use App\Models\Karya;
use App\Models\KaryaVersi;
use App\Models\Peserta;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class KaryaService
{
    public const MAX_WORKS_PER_INSTITUTION = 2;

    /**
     * Memastikan institusi kampus/sekolah belum melampaui batas maksimal 2 karya per edisi festival.
     * Karya dengan status 'tidak_lolos' tidak dihitung sebagai kuota usulan aktif.
     */
    public function assertInstitutionQuotaAvailable(int $festivalId, Peserta $peserta): void
    {
        $normalizedInstitution = mb_strtolower(trim((string) $peserta->institusi));

        if ($normalizedInstitution !== '' && in_array($peserta->jenis_peserta, ['mahasiswa', 'siswa', 'siswa_smk', 'pic'], true)) {
            $activeCount = Karya::query()
                ->where('festival_id', $festivalId)
                ->where('status', '!=', 'tidak_lolos')
                ->whereHas('submitter', function ($query) use ($normalizedInstitution) {
                    $query->whereRaw('LOWER(TRIM(institusi)) = ?', [$normalizedInstitution]);
                })
                ->count();

            if ($activeCount >= self::MAX_WORKS_PER_INSTITUTION) {
                throw ValidationException::withMessages([
                    'judul' => ['Batas maksimal 2 karya usulan per kampus/institusi pada edisi festival ini telah tercapai.'],
                ]);
            }

            return;
        }

        $ownActiveCount = Karya::query()
            ->where('festival_id', $festivalId)
            ->where('peserta_id', $peserta->id)
            ->where('status', '!=', 'tidak_lolos')
            ->count();

        if ($ownActiveCount >= self::MAX_WORKS_PER_INSTITUTION) {
            throw ValidationException::withMessages([
                'judul' => ['Batas maksimal 2 karya usulan per peserta pada edisi festival ini telah tercapai.'],
            ]);
        }
    }

    /**
     * Mencatat riwayat versi dan status kurasi karya beserta snapshot ringkas (BR-09).
     */
    public function recordRevision(
        Karya $karya,
        ?int $userId,
        ?string $statusLama,
        string $statusBaru,
        ?string $catatan = null
    ): KaryaVersi {
        $nextVersion = ((int) KaryaVersi::where('karya_id', $karya->id)->max('versi')) + 1;

        return KaryaVersi::create([
            'karya_id'    => $karya->id,
            'versi'       => $nextVersion,
            'diubah_oleh' => $userId,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'catatan'     => $catatan,
            'snapshot'    => [
                'judul'                => $karya->judul,
                'kategori'             => $karya->kategori,
                'teknologi'            => $karya->teknologi,
                'deskripsi'            => $karya->deskripsi,
                'masalah'              => $karya->masalah,
                'pengguna_sasaran'     => $karya->pengguna_sasaran,
                'solusi'               => $karya->solusi,
                'fitur_utama'          => $karya->fitur_utama,
                'tahap_kesiapan'       => $karya->tahap_kesiapan,
                'hasil_uji'            => $karya->hasil_uji,
                'keterbatasan'         => $karya->keterbatasan,
                'kebutuhan_kolaborasi' => $karya->kebutuhan_kolaborasi,
                'url_demo'             => $karya->url_demo,
            ],
            'created_at'  => now(),
        ]);
    }

    /**
     * Kirim notifikasi email pembaruan status kurasi kepada penanggung jawab karya.
     */
    public function notifyCurationStatus(Karya $karya, string $statusBaru, ?string $catatan = null): void
    {
        $karya->loadMissing('submitter');
        $email = $karya->submitter?->email;

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if (! in_array($statusBaru, ['dalam_pemeriksaan', 'perlu_revisi', 'lolos', 'tidak_lolos'], true)) {
            return;
        }

        try {
            Mail::to($email)->send(new CurationStatusMail($karya, $statusBaru, $catatan));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
