<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'nidn',
        'password',
        'jabatan',
        'prodi',
        'status',
        'foto',
        'beban_mengajar',
        'max_beban',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'beban_mengajar' => 'integer',
            'max_beban' => 'integer',
        ];
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function penelitians()
    {
        return $this->hasMany(Penelitian::class);
    }

    public function selfAssessments()
    {
        return $this->hasMany(SelfAssessment::class);
    }

    public function detailHasilRekomendasi()
    {
        return $this->hasMany(DetailHasilRekomendasi::class);
    }

    public function pendidikans()
    {
    return $this->hasMany(Pendidikan::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // ============================================
    // ROLE & PERMISSION HELPERS
    // ============================================

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return in_array($this->jabatan, $roles);
    }

    /**
     * Cek apakah user adalah Struktural
     */
    public function isStruktural()
    {
        return in_array($this->jabatan, [
            'Kepala Jurusan',
            'Sekretaris Jurusan',
            'Kepala Program Studi'
        ]);
    }

    /**
     * Cek apakah user adalah Dosen/Laboran biasa
     */
    public function isDosenBiasa()
    {
        return in_array($this->jabatan, ['Dosen', 'Laboran']);
    }

    /**
     * Get redirect URL berdasarkan role
     */
    public function getDashboardUrl()
    {
        if ($this->isStruktural()) {
            return route('dashboard.struktural');
        }
        return route('dashboard.dosen');
    }

    /**
     * Hitung persentase beban mengajar
     */
    public function getPersentaseBebanAttribute()
    {
        if ($this->max_beban == 0) return 0;
        return round(($this->beban_mengajar / $this->max_beban) * 100);
    }

    /**
     * Get color untuk progress bar beban mengajar
     */
    public function getBebanColorAttribute()
    {
        $persentase = $this->persentase_beban;
        
        if ($persentase >= 90) return 'green';
        if ($persentase >= 60) return 'blue';
        if ($persentase >= 40) return 'yellow';
        return 'red';
    }
}