<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublikasiKaryaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'karya_id'          => $this->karya_id,
            'kanal'             => $this->kanal,
            'slug'              => $this->slug,
            'judul_publik'      => $this->judul_publik,
            'ringkasan_publik'  => $this->ringkasan_publik,
            'thumbnail_file_id' => $this->thumbnail_file_id,
            'thumbnail_url'     => $this->thumbnailFile?->path ?? $this->karya?->thumbnail,
            'status'            => $this->status,
            'tanggal_terbit'    => $this->tanggal_terbit?->toIso8601String(),
            'tanggal_berakhir'  => $this->tanggal_berakhir?->toIso8601String(),
            'is_unggulan'       => (bool) $this->is_unggulan,
            'urutan_tampil'     => $this->urutan_tampil,
            'izin_peserta'      => (bool) $this->izin_peserta,
            'izin_tanggal'      => $this->izin_tanggal?->toIso8601String(),
            'alasan_penarikan'  => $this->alasan_penarikan,
            'meta_title'        => $this->meta_title,
            'meta_description'  => $this->meta_description,
            'jumlah_dilihat'    => $this->jumlah_dilihat,
            'jumlah_suka'       => $this->jumlah_suka,
            'created_at'        => $this->created_at?->toIso8601String(),
            'updated_at'        => $this->updated_at?->toIso8601String(),
            'karya'             => $this->whenLoaded('karya', function () {
                return [
                    'id'                   => $this->karya->id,
                    'festival_id'          => $this->karya->festival_id,
                    'judul'                => $this->karya->judul,
                    'deskripsi'            => $this->karya->deskripsi,
                    'masalah'              => $this->karya->masalah,
                    'pengguna_sasaran'     => $this->karya->pengguna_sasaran,
                    'solusi'               => $this->karya->solusi,
                    'fitur_utama'          => $this->karya->fitur_utama,
                    'kategori'             => $this->karya->kategori,
                    'teknologi'            => $this->karya->teknologi,
                    'tahap_kesiapan'       => $this->karya->tahap_kesiapan,
                    'hasil_uji'            => $this->karya->hasil_uji,
                    'keterbatasan'         => $this->karya->keterbatasan,
                    'kebutuhan_kolaborasi' => $this->karya->kebutuhan_kolaborasi,
                    'lisensi'              => $this->karya->lisensi,
                    'url_demo'             => $this->karya->url_demo,
                    'status'               => $this->karya->status,
                    'skor_akhir'           => $this->karya->skor_akhir,
                    'submitter'            => $this->whenLoaded('karya.submitter', function () {
                        return [
                            'id'           => $this->karya->submitter?->id,
                            'nama_lengkap' => $this->karya->submitter?->nama_lengkap,
                            'institusi'    => $this->karya->submitter?->institusi,
                        ];
                    }),
                    // Hanya tampilkan nama & peran tim ke publik (tanpa email/telepon/identitas pribadi)
                    'tim'                  => $this->when(
                        $this->karya->relationLoaded('anggotaTims') || $this->karya->relationLoaded('pesertas'),
                        function () {
                            $leaders = $this->karya->relationLoaded('pesertas')
                                ? $this->karya->pesertas->map(fn ($p) => [
                                    'nama_lengkap' => $p->nama_lengkap,
                                    'peran'        => $p->pivot?->peran ?? 'Ketua Tim',
                                    'is_ketua'     => (bool) ($p->pivot?->is_ketua ?? false),
                                ])
                                : collect();

                            $members = $this->karya->relationLoaded('anggotaTims')
                                ? $this->karya->anggotaTims->map(fn ($m) => [
                                    'nama_lengkap' => $m->nama_lengkap,
                                    'peran'        => $m->peran ?? 'Anggota',
                                    'is_ketua'     => (bool) $m->is_ketua,
                                ])
                                : collect();

                            return $leaders->concat($members)->values();
                        }
                    ),
                    'files'                => $this->whenLoaded('karya.files', function () {
                        return $this->karya->files->map(fn ($f) => [
                            'id'            => $f->id,
                            'jenis'         => $f->jenis,
                            'nama_asli'     => $f->nama_asli,
                            'path'          => $f->path,
                            'url_eksternal' => $f->url_eksternal,
                        ]);
                    }),
                ];
            }),
            'logs'              => PublikasiLogResource::collection($this->whenLoaded('logs')),
        ];
    }
}
