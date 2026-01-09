<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Kirim notifikasi ke user tertentu
     */
    public static function send($userId, $type, $title, $message, $link = null, $icon = 'bell')
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'icon' => $icon,
            'is_read' => false
        ]);
    }

    /**
     * Kirim notifikasi ke semua user dengan jabatan tertentu
     */
    public static function sendToRole($jabatan, $type, $title, $message, $link = null, $icon = 'bell')
    {
        $users = User::where('jabatan', $jabatan)->get();
        
        foreach ($users as $user) {
            self::send($user->id, $type, $title, $message, $link, $icon);
        }
    }

    /**
     * Kirim notifikasi ke semua STRUKTURAL
     */
    public static function sendToStruktural($type, $title, $message, $link = null, $icon = 'bell')
    {
        // Ambil user dengan jabatan Struktural (Kajur, Sekjur, Kaprodi)
        $users = User::whereIn('jabatan', [
            'Kepala Jurusan',
            'Sekretaris Jurusan', 
            'Kepala Program Studi'
        ])->get();
        
        foreach ($users as $user) {
            self::send($user->id, $type, $title, $message, $link, $icon);
        }
    }

    /**
     * Kirim notifikasi ke semua DOSEN & LABORAN
     */
    public static function sendToDosenLaboran($type, $title, $message, $link = null, $icon = 'bell')
    {
        $users = User::whereIn('jabatan', ['Dosen', 'Laboran'])->get();
        
        foreach ($users as $user) {
            self::send($user->id, $type, $title, $message, $link, $icon);
        }
    }

    /**
     * Notifikasi untuk Self-Assessment Baru
     * Link = NULL karena struktural tidak perlu redirect ke mana-mana
     */
    public static function selfAssessmentCreated($dosenName, $mataKuliah)
    {
        self::sendToStruktural(
            'self_assessment',
            'Self-Assessment Baru',
            "{$dosenName} telah mengupdate self-assessment untuk {$mataKuliah}",
            null, // ← NULL = cukup baca notifikasi, tidak redirect
            'clipboard-check'
        );
    }

    /**
     * Notifikasi untuk Dosen Baru
     */
    public static function dosenCreated($dosenName)
    {
        self::sendToStruktural(
            'new_dosen',
            'Dosen Baru Ditambahkan',
            "Dosen baru telah ditambahkan: {$dosenName}",
            route('struktural.dosen.index'),
            'user-plus'
        );
    }

    /**
     * Notifikasi untuk Mata Kuliah Baru
     */
    public static function mataKuliahCreated($mataKuliahName)
    {
        self::sendToStruktural(
            'new_matakuliah',
            'Mata Kuliah Baru',
            "Mata kuliah baru telah ditambahkan: {$mataKuliahName}",
            route('struktural.matakuliah.index'),
            'book'
        );
    }

    /**
     * Notifikasi Reminder untuk Dosen
     */
    public static function reminderSelfAssessment($dosenId, $deadline)
    {
        self::send(
            $dosenId,
            'reminder',
            'Reminder: Self-Assessment',
            "Jangan lupa mengisi self-assessment! Deadline: {$deadline}",
            route('dosen.self-assessment.index'),
            'clock'
        );
    }

    /**
     * Notifikasi Self-Assessment Disetujui
     */
    public static function selfAssessmentApproved($dosenId, $mataKuliah)
    {
        self::send(
            $dosenId,
            'approval',
            'Self-Assessment Disetujui',
            "Self-assessment Anda untuk {$mataKuliah} telah disetujui",
            route('dosen.self-assessment.index'),
            'check-circle'
        );
    }

    /**
     * Notifikasi Rekomendasi Siap
     */
    public static function recommendationReady()
    {
        self::sendToStruktural(
            'recommendation',
            'Rekomendasi Siap',
            'Rekomendasi semester baru telah di-generate dan siap direview',
            route('struktural.rekomendasi.index'),
            'star'
        );
    }
}