<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 *
 * Resource ini digunakan untuk mentransformasi Model User menjadi array JSON yang rapi
 * sebelum dikirimkan ke client, memastikan field sensitif (seperti password) tidak terekspos.
 */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'jabatan' => $this->jabatan,
            'nidn' => $this->nidn,
            'roles' => $this->whenLoaded('roles', function () {
                // Hanya memuat roles jika sudah di-load (lazy loading)
                return $this->roles->pluck('name');
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
