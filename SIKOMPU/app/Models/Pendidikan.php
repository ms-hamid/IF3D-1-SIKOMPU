<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikan'; // opsional kalau nama table sesuai konvensi

    protected $fillable = [
        'user_id',       // wajib supaya bisa diisi otomatis
        'jenjang',
        'jurusan',
        'universitas',
        'tahun_lulus',
    ];

    // jika menggunakan soft delete
    protected $dates = ['deleted_at'];
}
