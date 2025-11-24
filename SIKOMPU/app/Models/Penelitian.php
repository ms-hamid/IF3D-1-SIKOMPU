<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitian';

    protected $fillable = [
        'user_id',
        'judul_penelitian',
        'tahun_publikasi',
        'peran',
        'link_publikasi',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessor untuk memastikan link selalu lengkap
    public function getLinkPublikasiAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Jika belum ada http/https, tambahkan
        if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
            return "https://" . $value;
        }
        
        return $value;
    }
}