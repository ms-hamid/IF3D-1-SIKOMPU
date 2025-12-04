<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'user_id',
        'nama_sertifikat',
        'institusi_pemberi',
        'tahun_diperoleh',
        'file_path',
        'status_verifikasi',
        'kategori_id',
    ];

    public function kategori()
    {   
    return $this->belongsTo(Kategori::class);
    }


    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
