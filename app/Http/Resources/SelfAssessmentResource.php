<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SelfAssessmentResource
 * @package App\Http\Resources
 *
 * Digunakan untuk mentransformasi Model SelfAssessment menjadi array JSON
 * yang menyertakan relasi Mata Kuliah yang terkait.
 */
class SelfAssessmentResource extends JsonResource
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
            'nilai' => $this->nilai, // Nilai/deskripsi hasil assessment (teks)
            'mata_kuliah' => [
                'id' => $this->mataKuliah->id,
                'kode_mk' => $this->mataKuliah->kode_mk,
                'nama_mk' => $this->mataKuliah->nama_mk,
            ],
            'assessed_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
