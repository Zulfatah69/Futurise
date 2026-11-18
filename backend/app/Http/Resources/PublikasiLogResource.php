<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublikasiLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'publikasi_id' => $this->publikasi_id,
            'status_lama'  => $this->status_lama,
            'status_baru'  => $this->status_baru,
            'diubah_oleh'  => $this->diubah_oleh,
            'updater_name' => $this->updater?->name,
            'catatan'      => $this->catatan,
            'created_at'   => $this->created_at?->toIso8601String(),
        ];
    }
}
