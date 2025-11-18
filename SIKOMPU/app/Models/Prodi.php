<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
    ];

    public $timestamps = false;

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'prodi_id');
    }
}
