<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenelitianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'judul' => $this->judul,
            'tahun' => $this->tahun,
            'bidang' => $this->bidang, // Bidang penelitian/keahlian
            'jenis_luaran' => $this->jenis_luaran, // Contoh: Jurnal, Prosiding, Paten, Buku
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // 'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
