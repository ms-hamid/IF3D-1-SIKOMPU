<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Matakuliah;


class AIIntegrationController extends Controller
{
    // URL Service Flask (Lokal)
    protected $flaskUrl = 'http://127.0.0.1:5000';

    /**
     * 1. Cek Koneksi ke Flask (GET)
     */
    public function checkConnection()
    {
        try {
            $response = Http::timeout(5)->get($this->flaskUrl . '/');
            
            if ($response->successful()) {
                return response()->json([
                    'laravel_status' => 'Connected',
                    'ai_service_response' => $response->json()
                ]);
            }
            return response()->json(['status' => 'Failed', 'error' => 'AI Service Error'], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Pastikan python app.py sudah jalan!'], 500);
        }
    }

    /**
     * 2. Generate Hasil Rekomendasi (POST ke Flask)
     */
    public function generateRecommendation()
    {
        // A. Ambil Data Real dari Database Laravel
        $users = User::with(['selfAssessments', 'penelitians', 'sertifikat', 'pendidikans'])->get();

        $dataDosen = [];
            $matkuls = Matakuliah::all();

        foreach ($users as $user) {
            foreach ($matkuls as $mk) {
                $dataDosen[] = [
                    'id' => $user->id,
                    'nama' => $user->nama_lengkap,
                    'kode_matkul' => $mk->kode_mk,
                    'c1_self_assessment' => optional($user->selfAssessments->last())->nilai ?? 1,
                    'c2_pendidikan' => optional($user->pendidikans->last())->jenjang ?? 1,
                    'c3_sertifikat' => $user->sertifikat->count() ?: 1,
                    'c4_penelitian' => $user->penelitians->count() ?: 1,
                ];
            }
        }

        // B. Kirim ke Flask
        try {
            $response = Http::asJson()->post($this->flaskUrl . '/api/predict', [ 'dosen' => $dataDosen 

            // $response = Http::post($this->flaskUrl . '/api/predict', [
            //     'dosen' => $dataDosen
            ]);

            if ($response->successful()) {
                $hasil = $response->json();
                
                // C. (Opsional) Simpan Hasil ke Database Laravel di sini
                
                return response()->json($hasil); // Tampilkan JSON ke Frontend
            } else {
                return response()->json(['error' => 'Gagal hitung di AI', 'detail' => $response->body()], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi Error', 'msg' => $e->getMessage()], 500);
        }
    }

    
}


