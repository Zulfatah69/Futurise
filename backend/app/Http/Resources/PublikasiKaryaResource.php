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
                    'id'          => $this->karya->id,
                    'festival_id' => $this->karya->festival_id,
                    'judul'       => $this->karya->judul,
                    'kategori'    => $this->karya->kategori,
                    'teknologi'   => $this->karya->teknologi,
                    'url_demo'    => $this->karya->url_demo,
                    'status'      => $this->karya->status,
                    'skor_akhir'  => $this->karya->skor_akhir,
                    'submitter'   => $this->whenLoaded('karya.submitter', function () {
                        return [
                            'id'           => $this->karya->submitter?->id,
                            'nama_lengkap' => $this->karya->submitter?->nama_lengkap,
                            'institusi'    => $this->karya->submitter?->institusi,
                        ];
                    }),
                    'files'       => $this->whenLoaded('karya.files', function () {
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
