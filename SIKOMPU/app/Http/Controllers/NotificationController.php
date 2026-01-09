<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get notifications untuk dropdown (AJAX)
     * MASIH DIPAKE - Jangan dihapus!
     */
    public function getNotifications()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Tandai semua sebagai sudah dibaca
     * MASIH DIPAKE - Jangan dihapus!
     */
    public function markAllAsRead()
    {
        try {
            $updated = auth()->user()->notifications()
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            Log::info('Mark all as read:', ['updated' => $updated]);
            
            return response()->json([
                'success' => true,
                'updated' => $updated
            ]);
        } catch (\Exception $e) {
            Log::error('Mark all as read failed:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Hapus notifikasi
     * MASIH DIPAKE - Jangan dihapus!
     */
    public function destroy($id)
    {
        try {
            $notification = auth()->user()
                ->notifications()
                ->findOrFail($id);
            
            $notification->delete();
            
            Log::info('Notification deleted:', ['id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete notification failed:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus notifikasi'
            ], 500);
        }
    }
}