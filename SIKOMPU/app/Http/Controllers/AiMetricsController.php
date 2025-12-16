<?php

namespace App\Http\Controllers;

use App\Models\AiPrediction;
use App\Models\HasilRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiMetricsController extends Controller
{
    /**
     * Halaman utama AI Metrics Dashboard
     * Auto-sync dari tabel hasil_rekomendasi
     */
    public function index()
    {
        // Sync data dari hasil_rekomendasi ke ai_predictions
        $this->syncFromHasilRekomendasi();

        // Statistik umum
        $stats = [
            'total' => AiPrediction::getTotalPredictions(),
            'verified' => AiPrediction::getVerifiedCount(),
            'pending' => AiPrediction::getPendingCount(),
        ];

        // Ambil confusion matrix dan metrics
        $confusionMatrix = AiPrediction::getConfusionMatrix();
        
        $metrics = [
            'accuracy' => AiPrediction::getAccuracy(),
            'precision' => AiPrediction::getPrecision(),
            'recall' => AiPrediction::getRecall(),
            'f1_score' => AiPrediction::getF1Score(),
        ];
        
        return view('pages.peforma-ai', compact('metrics', 'confusionMatrix', 'stats'));
    }

    /**
     * Sync data dari hasil_rekomendasi ke ai_predictions
     * Otomatis set actual_status berdasarkan yang dipilih
     */
    private function syncFromHasilRekomendasi()
    {
        try {
            // Ambil hasil rekomendasi yang sudah final/approved
            $hasilRekomendasi = DB::table('hasil_rekomendasi')
                ->join('hasil_rekomendasi_detail', 'hasil_rekomendasi.id', '=', 'hasil_rekomendasi_detail.hasil_rekomendasi_id')
                ->select(
                    'hasil_rekomendasi_detail.user_id as dosen_id',
                    'hasil_rekomendasi.mata_kuliah_id',
                    'hasil_rekomendasi_detail.ranking',
                    'hasil_rekomendasi_detail.skor',
                    'hasil_rekomendasi.created_at'
                )
                ->whereNotNull('hasil_rekomendasi.mata_kuliah_id')
                ->get();

            foreach ($hasilRekomendasi as $hasil) {
                // Cek apakah sudah ada di ai_predictions
                $existing = AiPrediction::where('dosen_id', $hasil->dosen_id)
                    ->whereJsonContains('features_used->mata_kuliah_id', $hasil->mata_kuliah_id)
                    ->first();

                if (!$existing) {
                    // Tentukan predicted_status berdasarkan ranking
                    $predictedStatus = $hasil->ranking <= 3 ? 'diterima' : 'ditolak';
                    
                    // Tentukan actual_status berdasarkan ranking
                    // Asumsi: ranking 1 = yang dipilih (diterima), sisanya = ditolak
                    $actualStatus = $hasil->ranking == 1 ? 'diterima' : 'ditolak';

                    AiPrediction::create([
                        'dosen_id' => $hasil->dosen_id,
                        'predicted_status' => $predictedStatus,
                        'actual_status' => $actualStatus,
                        'confidence_score' => $hasil->skor ?? 0,
                        'features_used' => json_encode([
                            'mata_kuliah_id' => $hasil->mata_kuliah_id,
                            'ranking' => $hasil->ranking,
                        ]),
                        'predicted_at' => $hasil->created_at,
                        'is_verified' => true,
                        'verified_at' => now(),
                    ]);
                }
            }

            \Log::info("✅ AI Predictions synced from hasil_rekomendasi");
        } catch (\Exception $e) {
            \Log::error("❌ Failed to sync AI predictions: " . $e->getMessage());
        }
    }

    /**
     * Halaman verifikasi prediksi (opsional, jika butuh koreksi manual)
     */
    public function verification()
    {
        $predictions = AiPrediction::with('dosen')
            ->where('actual_status', 'pending')
            ->orderBy('predicted_at', 'desc')
            ->paginate(20);

        return view('pages.verifikasi-ai', compact('predictions'));
    }

    /**
     * Update status aktual manual (jika ada koreksi)
     */
    public function updateActualStatus(Request $request, $id)
    {
        $request->validate([
            'actual_status' => 'required|in:diterima,ditolak',
        ]);

        $prediction = AiPrediction::findOrFail($id);
        
        $prediction->update([
            'actual_status' => $request->actual_status,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status berhasil diverifikasi!');
    }

    /**
     * Refresh data (re-sync dari hasil_rekomendasi)
     */
    public function refresh()
    {
        $this->syncFromHasilRekomendasi();
        
        return redirect()->route('ai.performa')
            ->with('success', 'Data berhasil di-refresh dari hasil rekomendasi!');
    }

    /**
     * Export metrics report
     */
    public function exportReport()
    {
        $metrics = [
            'accuracy' => AiPrediction::getAccuracy(),
            'precision' => AiPrediction::getPrecision(),
            'recall' => AiPrediction::getRecall(),
            'f1_score' => AiPrediction::getF1Score(),
        ];

        $confusionMatrix = AiPrediction::getConfusionMatrix();

        $stats = [
            'total' => AiPrediction::getTotalPredictions(),
            'verified' => AiPrediction::getVerifiedCount(),
            'pending' => AiPrediction::getPendingCount(),
        ];

        return response()->json([
            'generated_at' => now(),
            'statistics' => $stats,
            'metrics' => $metrics,
            'confusion_matrix' => $confusionMatrix,
        ]);
    }
}