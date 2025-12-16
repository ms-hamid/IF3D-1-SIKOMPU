<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHasilRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'detail_hasil_rekomendasi';

    protected $fillable = [
        'hasil_id',
        'matakuliah_id',
        'user_id',
        'peran_penugasan',
        'skor_dosen_di_mk',
    ];

    public $timestamps = false;

    public function hasilRekomendasi()
    {
        return $this->belongsTo(HasilRekomendasi::class, 'hasil_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPeranPenugasanLowerAttribute()
    {
    return strtolower($this->peran_penugasan);
    }
}
