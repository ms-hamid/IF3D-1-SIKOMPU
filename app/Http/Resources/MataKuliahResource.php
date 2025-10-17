<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MataKuliahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'kode_mk' => $this->kode_mk,
            'nama_mk' => $this->nama_mk,
            'sks' => $this->sks,
            
            // Memuat relasi Prodi menggunakan ProdiResource (jika sudah di-load)
            'prodi' => new ProdiResource($this->whenLoaded('prodi')),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
