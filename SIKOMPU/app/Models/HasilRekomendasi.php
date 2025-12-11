<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'hasil_rekomendasi';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
        'status',
    ];

    public function detailHasilRekomendasi()
    {
        return $this->hasMany(DetailHasilRekomendasi::class, 'hasil_id');
    }
}
