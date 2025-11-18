<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailRekomendasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hasil_rekomendasi_id' => $this->hasil_rekomendasi_id,
            'user' => new UserResource($this->whenLoaded('user')), // Dosen/Laboran yang dievaluasi
            'peran_yang_dievaluasi' => $this->peran_yang_dievaluasi, // Contoh: 'Koordinator', 'Pengampu'
            'skor_total' => $this->skor_total,
            'keterangan_skor' => $this->keterangan_skor, // Detail komponen skor (JSON/Text)
            'status_penetapan' => $this->status_penetapan, // Apakah user ini yang dipilih?
        ];
    }
}
