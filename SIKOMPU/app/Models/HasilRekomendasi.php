<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilRekomendasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hasil_rekomendasi';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke detail hasil rekomendasi
     */
    public function detailHasilRekomendasi()
    {
        return $this->hasMany(DetailHasilRekomendasi::class, 'hasil_id');
    }

    /**
     * Alias untuk compatibility
     */
    public function details()
    {
        return $this->detailHasilRekomendasi();
    }

    /**
     * Scope: Hanya ambil hasil yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by semester
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}