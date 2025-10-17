<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
        protected $fillable = [
            'nama',
            'nidn',
            'password',
            'jabatan',
        ];

        public function sertifikat()
        {
            return $this->hasMany(Sertifikat::class);
        }

        public function penelitian()
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

        public function roles()
        {
            return $this->belongsToMany(Role::class, 'user_roles');
        }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
