<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use App\Models\User; // Uncomment jika pakai Model User

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
        // A. Ambil Data Real dari Database Laravel (Contoh Dummy)
        // Nanti ganti dengan: $dosen = User::where('role', 'dosen')->get();

        // Ambil data dari database
        // $dosenMatkul = \DB::table('dosen')
        //     ->select(
        //         'id',
        //         'nama',
        //         'kode_matkul',
        //         'self_assessment',
        //         'pendidikan',
        //         'sertifikat',
        //         'penelitian'
        //     )
        //     ->get();

        // if ($dosenMatkul->isEmpty()) {
        //     return response()->json(['error' => 'Database kosong'], 400);
        // }

        // Format data untuk API Flask
        // $dataDosen = $dosenMatkul->map(function($d) {
        //     return [
        //         'id' => $d->id,
        //         'nama' => $d->nama,
        //         'kode_matkul' => $d->kode_matkul,
        //         'c1_self_assessment' => $d->self_assessment ?? 0,
        //         'c2_pendidikan' => $d->pendidikan,
        //         'c3_sertifikat' => $d->sertifikat,
        //         'c4_penelitian' => $d->penelitian,
        //     ];
        // })->toArray();


        // Data Dummy
        $dataDosen = [
            // ======================== Dr. Budi (S3) ========================
            [
                'id' => 1,
                'nama' => 'Dr. Budi (S3)',
                'kode_matkul' => 'IF101',
                'c1_self_assessment' => 5,
                'c2_pendidikan' => 5,
                'c3_sertifikat' => 4,
                'c4_penelitian' => 1
            ],
            [
                'id' => 2,
                'nama' => 'Dr. Budi (S3)',
                'kode_matkul' => 'IF202',
                'c1_self_assessment' => 8,
                'c2_pendidikan' => 5,
                'c3_sertifikat' => 5,
                'c4_penelitian' => 8
            ],
            [
                'id' => 3,
                'nama' => 'Dr. Budi (S3)',
                'kode_matkul' => 'IF303',
                'c1_self_assessment' => 1,
                'c2_pendidikan' => 3,
                'c3_sertifikat' => 5,
                'c4_penelitian' => 1
            ],

            // ======================== Ani, M.Kom (S2) ========================
            [
                'id' => 4,
                'nama' => 'Ani, M.Kom (S2)',
                'kode_matkul' => 'IF101',
                'c1_self_assessment' => 8,
                'c2_pendidikan' => 5,
                'c3_sertifikat' => 7,
                'c4_penelitian' => 4
            ],
            [
                'id' => 5,
                'nama' => 'Ani, M.Kom (S2)',
                'kode_matkul' => 'IF202',
                'c1_self_assessment' => 7,
                'c2_pendidikan' => 3,
                'c3_sertifikat' => 6,
                'c4_penelitian' => 5
            ],
            [
                'id' => 3,
                'nama' => 'Ani, M.Kom (S3)',
                'kode_matkul' => 'IF303',
                'c1_self_assessment' => 4,
                'c2_pendidikan' => 5,
                'c3_sertifikat' => 1,
                'c4_penelitian' => 1
            ],

            // ======================== Rizal, M.T (S2) ========================
            [
                'id' => 7,
                'nama' => 'Rizal, M.T (S2)',
                'kode_matkul' => 'IF101',
                'c1_self_assessment' => 7,
                'c2_pendidikan' => 3,
                'c3_sertifikat' => 5,
                'c4_penelitian' => 6
            ],
            [
                'id' => 8,
                'nama' => 'Rizal, M.T (S2)',
                'kode_matkul' => 'IF202',
                'c1_self_assessment' => 6,
                'c2_pendidikan' => 3,
                'c3_sertifikat' => 4,
                'c4_penelitian' => 7
            ],
            [
                'id' => 9,
                'nama' => 'Rizal, M.T (S2)',
                'kode_matkul' => 'IF303',
                'c1_self_assessment' => 8,
                'c2_pendidikan' => 3,
                'c3_sertifikat' => 5,
                'c4_penelitian' => 4
            ],
        ];


        // B. Kirim ke Flask
        try {
            $response = Http::post($this->flaskUrl . '/api/predict', [
                'dosen' => $dataDosen
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