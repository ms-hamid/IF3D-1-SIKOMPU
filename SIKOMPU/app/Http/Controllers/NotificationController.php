<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Ambil semua notifikasi user yang login
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('pages.notifications', compact('notifications'));
    }

    // Get notifications untuk dropdown (AJAX)
    public function getNotifications()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = auth()->user()
            ->notifications()
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Tandai sebagai sudah dibaca
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    // Tandai semua sebagai sudah dibaca
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai sudah dibaca'
        ]);
    }

    // Hapus notifikasi
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }

    // Hapus semua notifikasi yang sudah dibaca
    public function deleteRead()
    {
        auth()->user()
            ->notifications()
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi yang sudah dibaca berhasil dihapus'
        ]);
    }
}