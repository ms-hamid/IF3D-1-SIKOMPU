<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;
use Illuminate\Http\Request;
use App\Http\Resources\DetailRekomendasiResource;

class DetailRekomendasiController extends Controller
{
    /**
     * Tampilkan semua detail skor untuk satu Hasil Rekomendasi tertentu.
     * Endpoint: GET /api/hasil-rekomendasis/{hasilRekomendasi}/details
     */
    public function index(HasilRekomendasi $hasilRekomendasi)
    {
        $details = $hasilRekomendasi->details()->with('user')->get();
        
        return DetailRekomendasiResource::collection($details);
    }
}
