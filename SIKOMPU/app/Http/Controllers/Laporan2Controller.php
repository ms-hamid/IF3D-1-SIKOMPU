<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Laporan2Controller extends Controller
{
    /**
     * Tampilkan pusat laporan.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Di sini Anda bisa mengambil data laporan terbaru, jika ada
        $recentReports = []; // Placeholder data

        return view('pages.laporan-dosen', [
            'reports' => $recentReports
        ]);
    }
}
