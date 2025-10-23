<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdiResource extends JsonResource
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
            'kode_prodi' => $this->kode_prodi,
            'nama_prodi' => $this->nama_prodi,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
