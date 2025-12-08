<?php

namespace App\Http\Controllers;

use App\Models\HasilRekomendasi;

class HasilRekomendasiPageController extends Controller
{
    public function index()
    {
        // Ambil seluruh hasil rekomendasi + relasi
        $hasil = HasilRekomendasi::with([
            'detailHasilRekomendasi.mataKuliah',
            'detailHasilRekomendasi.user'
        ])->get();

        return view('pages.hasil-rekomendasi', [
            'hasilRekomendasi' => $hasil
        ]);
    }
}
