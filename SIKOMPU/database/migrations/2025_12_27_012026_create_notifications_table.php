<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // self_assessment, new_dosen, reminder, dll
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable(); // URL untuk redirect
            $table->string('icon')->default('bell'); // icon untuk notifikasi
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};