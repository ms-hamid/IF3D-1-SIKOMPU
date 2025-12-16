<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel users (dosen)
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            
            // Foreign key ke mata kuliah
            $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliah')->nullOnDelete();
            
            // Status prediksi AI
            $table->enum('predicted_status', ['diterima', 'ditolak']);
            
            // Status aktual (nanti diisi dari hasil rekomendasi)
            $table->enum('actual_status', ['diterima', 'ditolak', 'pending'])->default('pending');
            
            // Skor dari AI (0-100)
            $table->decimal('confidence_score', 8, 2)->nullable();
            
            // Data pendukung dalam JSON
            $table->json('features_used')->nullable();
            
            // Timestamp prediksi
            $table->timestamp('predicted_at');
            
            // Status verifikasi
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['predicted_status', 'actual_status']);
            $table->index('predicted_at');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
    }
};