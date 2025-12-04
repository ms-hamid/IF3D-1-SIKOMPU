<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['nama'];

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function penelitian()
    {
        return $this->hasMany(Penelitian::class);
    }

    public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'mk_kategori')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
