<?php

namespace App\Http\Controllers;

use App\Models\SelfAssessment;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Imports\MataKuliahImport;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfAssessmentController extends Controller
{
    /**
     * Tampilkan halaman self assessment untuk dosen
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua prodi untuk filter
        $prodis = Prodi::all();
        
        // Query matakuliah
        $query = MataKuliah::with('prodi');
        
        // Filter by prodi jika ada
        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $query->where('prodi_id', $request->prodi_id);
        }
        
        // Paginate
        $mataKuliahs = $query->paginate(15);
        
        // Ambil self assessment yang sudah diisi user ini
        $assessments = SelfAssessment::where('user_id', $user->id)
                                      ->pluck('catatan', 'matakuliah_id')
                                      ->toArray();
        
        $ratings = SelfAssessment::where('user_id', $user->id)
                                  ->pluck('nilai', 'matakuliah_id')
                                  ->toArray();
        
        // Hitung progress
        $totalMatakuliah = MataKuliah::count();
        $selesai = SelfAssessment::where('user_id', $user->id)
                                 ->where('nilai', '>', 0)
                                 ->count();
        $progress = $totalMatakuliah > 0 ? round(($selesai / $totalMatakuliah) * 100) : 0;
        
        return view('pages.self-assessment', compact('mataKuliahs', 'prodis', 'ratings', 'assessments', 'selesai', 'totalMatakuliah', 'progress'));
    }
    
    /**
     * Tampilkan form import (untuk struktural/admin)
     */
    public function importForm()
    {
        return view('components.import');
    }
    
    /**
     * Proses import Excel (untuk struktural/admin)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);
        
        try {
            $file = $request->file('file');
            $filePath = $file->getRealPath();
            
            // Import menggunakan PhpSpreadsheet
            $importer = new MataKuliahImport();
            $importer->import($filePath);

            // ✨ NOTIFIKASI - Import berhasil ✨
            NotificationService::sendToStruktural(
                'import',
                'Import Self-Assessment Berhasil',
                'Data self-assessment berhasil diimport ke sistem',
                route('self-assessment.index'),
                'file-import'
            );
            
            return redirect()->back()->with('success', 'Data matakuliah berhasil diimport!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
    
    /**
     * Simpan self assessment dosen
     */
    public function store(Request $request)
    {
        $request->validate([
            'assessments' => 'required|array',
            'assessments.*.matakuliah_id' => 'required|exists:mata_kuliah,id',
            'assessments.*.nilai' => 'required|integer|min:0|max:8',
            'assessments.*.catatan' => 'nullable|string|max:1000',
        ]);

        $jumlahDisimpan = 0;
        $mataKuliahList = [];
    
        foreach ($request->assessments as $assessment) {
            // Skip jika nilai 0 (belum diisi)
            if ($assessment['nilai'] == 0) {
                continue;
            }
        
            SelfAssessment::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'matakuliah_id' => $assessment['matakuliah_id'],
                ],
                [
                    'nilai' => $assessment['nilai'],
                    'catatan' => $assessment['catatan'] ?? '',
                ]
            );

            $mataKuliah = MataKuliah::find($assessment['matakuliah_id']);
            if ($mataKuliah) {
                $mataKuliahList[] = $mataKuliah->nama_mk;
                $jumlahDisimpan++;
            }
        }

        // ✨ KIRIM NOTIFIKASI KE STRUKTURAL ✨
        if ($jumlahDisimpan > 0) {
            $user = Auth::user();
            
            $mataKuliahText = count($mataKuliahList) <= 3 
                ? implode(', ', $mataKuliahList)
                : implode(', ', array_slice($mataKuliahList, 0, 3)) . ' dan ' . (count($mataKuliahList) - 3) . ' lainnya';
            
            NotificationService::sendToStruktural(
                'self_assessment',
                'Self-Assessment Baru',
                "{$user->nama_lengkap} mengupdate self-assessment untuk {$jumlahDisimpan} mata kuliah: {$mataKuliahText}",
                route('self-assessment.index'),
                'clipboard-check'
            );
        }
    
        return redirect()->route('self-assessment.index')->with('success', 'Penilaian berhasil disimpan!');
    }
}