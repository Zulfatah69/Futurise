<?php

namespace App\Services;

use App\Models\Karya;
use App\Models\PublikasiKarya;
use App\Models\PublikasiLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublikasiService
{
    /**
     * Membuat atau memperbarui draft publikasi suatu karya.
     */
    public function createOrUpdateDraft(Karya $karya, array $data, User $actor): PublikasiKarya
    {
        return DB::transaction(function () use ($karya, $data, $actor) {
            $kanal = $data['kanal'] ?? 'website';
            
            $publikasi = PublikasiKarya::firstOrNew([
                'karya_id' => $karya->id,
                'kanal'    => $kanal,
            ]);

            $isNew = ! $publikasi->exists;
            $oldStatus = $publikasi->status;

            $judulPublik = $data['judul_publik'] ?? $publikasi->judul_publik ?? $karya->judul;
            $ringkasan = $data['ringkasan_publik'] ?? $publikasi->ringkasan_publik ?? Str::limit($karya->deskripsi, 450);

            $publikasi->judul_publik = $judulPublik;
            $publikasi->ringkasan_publik = $ringkasan;

            if (isset($data['slug']) && ! empty($data['slug'])) {
                $publikasi->slug = Str::slug($data['slug']);
            }

            if (isset($data['thumbnail_file_id'])) {
                $publikasi->thumbnail_file_id = $data['thumbnail_file_id'];
            }

            if (isset($data['is_unggulan'])) {
                $publikasi->is_unggulan = (bool) $data['is_unggulan'];
            }

            if (isset($data['urutan_tampil'])) {
                $publikasi->urutan_tampil = (int) $data['urutan_tampil'];
            }

            if (isset($data['izin_peserta'])) {
                $publikasi->izin_peserta = (bool) $data['izin_peserta'];
                if ($publikasi->izin_peserta && ! $publikasi->izin_tanggal) {
                    $publikasi->izin_tanggal = now();
                }
            }

            if (isset($data['meta_title'])) {
                $publikasi->meta_title = Str::limit($data['meta_title'], 70, '');
            }

            if (isset($data['meta_description'])) {
                $publikasi->meta_description = Str::limit($data['meta_description'], 160, '');
            }

            if (isset($data['tanggal_terbit'])) {
                $publikasi->tanggal_terbit = $data['tanggal_terbit'];
            }

            if (isset($data['tanggal_berakhir'])) {
                $publikasi->tanggal_berakhir = $data['tanggal_berakhir'];
            }

            if ($isNew) {
                $publikasi->status = 'draft';
            }

            $publikasi->save();

            if ($isNew) {
                $this->recordLog($publikasi, null, 'draft', $actor, 'Draft publikasi karya dibuat.');
            }

            return $publikasi;
        });
    }

    /**
     * Mengajukan karya untuk dikurasi & disetujui publikasinya.
     */
    public function submitForApproval(PublikasiKarya $publikasi, User $actor, ?string $notes = null): PublikasiKarya
    {
        return DB::transaction(function () use ($publikasi, $actor, $notes) {
            $oldStatus = $publikasi->status;

            if (! $publikasi->izin_peserta) {
                $publikasi->izin_peserta = true;
                $publikasi->izin_tanggal = now();
            }

            $publikasi->status = 'menunggu_persetujuan';
            $publikasi->save();

            $this->recordLog(
                $publikasi,
                $oldStatus,
                'menunggu_persetujuan',
                $actor,
                $notes ?? 'Diajukan untuk kurasi publikasi showcase.'
            );

            return $publikasi;
        });
    }

    /**
     * Menyetujui publikasi karya (kurasi lulus).
     */
    public function approve(PublikasiKarya $publikasi, User $approver, ?string $notes = null): PublikasiKarya
    {
        return DB::transaction(function () use ($publikasi, $approver, $notes) {
            $oldStatus = $publikasi->status;

            $now = now();
            $targetStatus = 'terbit';
            if ($publikasi->tanggal_terbit && $publikasi->tanggal_terbit->isFuture()) {
                $targetStatus = 'terjadwal';
            } else {
                $publikasi->tanggal_terbit = $now;
            }

            $publikasi->status = $targetStatus;
            $publikasi->disetujui_oleh = $approver->id;
            $publikasi->save();

            // Set persetujuan_publikasi di master karya
            $publikasi->karya()->update(['persetujuan_publikasi' => true]);

            $this->recordLog(
                $publikasi,
                $oldStatus,
                $targetStatus,
                $approver,
                $notes ?? 'Disetujui untuk publikasi showcase.'
            );

            return $publikasi;
        });
    }

    /**
     * Menerbitkan langsung ke showcase online.
     */
    public function publish(PublikasiKarya $publikasi, User $publisher, ?string $notes = null): PublikasiKarya
    {
        return DB::transaction(function () use ($publikasi, $publisher, $notes) {
            $oldStatus = $publikasi->status;

            $publikasi->status = 'terbit';
            if (! $publikasi->tanggal_terbit) {
                $publikasi->tanggal_terbit = now();
            }
            $publikasi->disetujui_oleh = $publisher->id;
            $publikasi->save();

            $publikasi->karya()->update(['persetujuan_publikasi' => true]);

            $this->recordLog(
                $publikasi,
                $oldStatus,
                'terbit',
                $publisher,
                $notes ?? 'Dipublikasikan live ke etalase expo.'
            );

            return $publikasi;
        });
    }

    /**
     * Menarik kembali karya dari peredaran publikasi showcase.
     */
    public function withdraw(PublikasiKarya $publikasi, User $actor, string $reason): PublikasiKarya
    {
        return DB::transaction(function () use ($publikasi, $actor, $reason) {
            $oldStatus = $publikasi->status;

            $publikasi->status = 'ditarik';
            $publikasi->alasan_penarikan = $reason;
            $publikasi->save();

            $this->recordLog(
                $publikasi,
                $oldStatus,
                'ditarik',
                $actor,
                'Penarikan publikasi: ' . $reason
            );

            return $publikasi;
        });
    }

    /**
     * Mencatat audit trail pada publikasi_log.
     */
    public function recordLog(
        PublikasiKarya $publikasi,
        ?string $oldStatus,
        string $newStatus,
        ?User $actor,
        ?string $notes = null
    ): PublikasiLog {
        return PublikasiLog::create([
            'publikasi_id' => $publikasi->id,
            'status_lama'  => $oldStatus,
            'status_baru'  => $newStatus,
            'diubah_oleh'  => $actor?->id,
            'catatan'      => $notes,
            'created_at'   => now(),
        ]);
    }

    /**
     * Mengambil daftar karya untuk showcase publik dengan filter, pencarian, dan paginasi.
     */
    public function getPublicShowcase(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = PublikasiKarya::published()
            ->with([
                'karya.submitter',
                'karya.festival',
                'karya.files',
                'thumbnailFile',
            ]);

        // Filter berdasarkan kategori lomba karya
        if (! empty($filters['kategori'])) {
            $query->whereHas('karya', function ($q) use ($filters) {
                $q->where('kategori', $filters['kategori']);
            });
        }

        // Filter berdasarkan festival edisi
        if (! empty($filters['festival_id'])) {
            $query->whereHas('karya', function ($q) use ($filters) {
                $q->where('festival_id', $filters['festival_id']);
            });
        }

        // Pencarian judul / teknologi / nama institusi
        if (! empty($filters['search'])) {
            $search = '%' . trim($filters['search']) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('judul_publik', 'like', $search)
                  ->orWhere('ringkasan_publik', 'like', $search)
                  ->orWhereHas('karya', function ($kq) use ($search) {
                      $kq->where('judul', 'like', $search)
                         ->orWhere('teknologi', 'like', $search)
                         ->orWhereHas('submitter', function ($sq) use ($search) {
                             $sq->where('nama_lengkap', 'like', $search)
                                ->orWhere('institusi', 'like', $search);
                         });
                  });
            });
        }

        // Filter hanya unggulan jika diminta
        if (! empty($filters['unggulan'])) {
            $query->where('is_unggulan', true);
        }

        // Urutan tampil: Prioritaskan is_unggulan, urutan_tampil, lalu filter sort
        $sort = $filters['sort'] ?? 'terbaru';
        $query->orderBy('is_unggulan', 'desc')
              ->orderBy('urutan_tampil', 'asc');

        if ($sort === 'terpopuler') {
            $query->orderBy('jumlah_dilihat', 'desc');
        } elseif ($sort === 'terfavorit') {
            $query->orderBy('jumlah_suka', 'desc');
        } else {
            $query->orderBy('tanggal_terbit', 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * Tambah jumlah tayangan (view count) dengan debounce sederhana.
     */
    public function incrementView(PublikasiKarya $publikasi): void
    {
        $publikasi->increment('jumlah_dilihat');
    }

    /**
     * Tambah jumlah suka (like count).
     */
    public function incrementLike(PublikasiKarya $publikasi): int
    {
        $publikasi->increment('jumlah_suka');
        return $publikasi->fresh()->jumlah_suka;
    }
}
