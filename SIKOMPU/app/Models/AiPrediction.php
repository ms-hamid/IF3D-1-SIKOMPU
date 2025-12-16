<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPrediction extends Model
{
    protected $fillable = [
        'dosen_id',
        'predicted_status',
        'actual_status',
        'confidence_score',
        'features_used',
        'predicted_at',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'features_used' => 'array',
        'predicted_at' => 'datetime',
        'verified_at' => 'datetime',
        'confidence_score' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    // =====================================
    // RELASI
    // =====================================
    
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =====================================
    // HITUNG CONFUSION MATRIX
    // (Hanya dari data yang sudah verified)
    // =====================================
    
    public static function getConfusionMatrix()
    {
        $verified = self::where('is_verified', true)
            ->where('actual_status', '!=', 'pending');

        $truePositive = (clone $verified)
            ->where('predicted_status', 'diterima')
            ->where('actual_status', 'diterima')
            ->count();

        $falsePositive = (clone $verified)
            ->where('predicted_status', 'diterima')
            ->where('actual_status', 'ditolak')
            ->count();

        $falseNegative = (clone $verified)
            ->where('predicted_status', 'ditolak')
            ->where('actual_status', 'diterima')
            ->count();

        $trueNegative = (clone $verified)
            ->where('predicted_status', 'ditolak')
            ->where('actual_status', 'ditolak')
            ->count();

        return [
            'tp' => $truePositive,
            'fp' => $falsePositive,
            'fn' => $falseNegative,
            'tn' => $trueNegative,
        ];
    }

    // =====================================
    // HITUNG ACCURACY
    // =====================================
    
    public static function getAccuracy()
    {
        $matrix = self::getConfusionMatrix();
        $total = $matrix['tp'] + $matrix['fp'] + $matrix['fn'] + $matrix['tn'];
        
        if ($total == 0) return 0;
        
        return round((($matrix['tp'] + $matrix['tn']) / $total) * 100, 2);
    }

    // =====================================
    // HITUNG PRECISION
    // =====================================
    
    public static function getPrecision()
    {
        $matrix = self::getConfusionMatrix();
        $denominator = $matrix['tp'] + $matrix['fp'];
        
        if ($denominator == 0) return 0;
        
        return round($matrix['tp'] / $denominator, 2);
    }

    // =====================================
    // HITUNG RECALL
    // =====================================
    
    public static function getRecall()
    {
        $matrix = self::getConfusionMatrix();
        $denominator = $matrix['tp'] + $matrix['fn'];
        
        if ($denominator == 0) return 0;
        
        return round($matrix['tp'] / $denominator, 2);
    }

    // =====================================
    // HITUNG F1-SCORE
    // =====================================
    
    public static function getF1Score()
    {
        $precision = self::getPrecision();
        $recall = self::getRecall();
        
        if ($precision + $recall == 0) return 0;
        
        return round((2 * $precision * $recall) / ($precision + $recall), 2);
    }

    // =====================================
    // STATISTIK TAMBAHAN
    // =====================================
    
    public static function getTotalPredictions()
    {
        return self::count();
    }

    public static function getVerifiedCount()
    {
        return self::where('is_verified', true)->count();
    }

    public static function getPendingCount()
    {
        return self::where('actual_status', 'pending')->count();
    }
}