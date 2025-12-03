<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'prodi_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'sesi',
        'semester',
    ];

    public $timestamps = false;

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class, 'matakuliah_id');
    }

    public function detailHasilRekomendasi()
    {
        return $this->hasMany(DetailHasilRekomendasi::class, 'matakuliah_id');
    }

}
