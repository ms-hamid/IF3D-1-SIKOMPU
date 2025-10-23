<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SertifikatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nama_sertifikat' => $this->nama_sertifikat,
            'tahun_perolehan' => $this->tahun_perolehan,
            'penyelenggara' => $this->penyelenggara,
            'level' => $this->level, // Asumsi kolom ini ada di tabel Anda
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // Jika Anda perlu menampilkan data user, gunakan UserResource di sini:
            // 'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
