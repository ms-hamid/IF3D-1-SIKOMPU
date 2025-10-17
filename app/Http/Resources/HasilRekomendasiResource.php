<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HasilRekomendasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mata_kuliah' => new MataKuliahResource($this->whenLoaded('mataKuliah')),
            'koordinator_rekomendasi' => new UserResource($this->whenLoaded('koordinatorRekomendasi')), // User yang Direkomendasikan
            'pengampu_rekomendasi' => UserResource::collection($this->whenLoaded('pengampuRekomendasi')), // Koleksi User Pengampu
            'semester' => $this->semester,
            'tahun_akademik' => $this->tahun_akademik,
            'status' => $this->status, // Contoh: 'Draft', 'Finalized', 'Rejected'
            'ditetapkan_oleh' => new UserResource($this->whenLoaded('ditetapkanOleh')), // Admin yang menetapkan
            'tanggal_penetapan' => $this->tanggal_penetapan,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // Tambahkan detail rekomendasi sebagai relasi
            'details' => DetailRekomendasiResource::collection($this->whenLoaded('details')),
        ];
    }
}
