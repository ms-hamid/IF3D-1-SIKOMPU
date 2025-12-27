@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto" x-data="notificationPage()">
    
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua notifikasi Anda</p>
            </div>
            <div class="flex gap-2">
                <button 
                    @click="markAllAsRead()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                >
                    <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
                </button>
                <button 
                    @click="deleteAllRead()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                >
                    <i class="fas fa-trash mr-2"></i>Hapus yang Dibaca
                </button>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bell text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Notifikasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $notifications->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Belum Dibaca</p>
                    <p class="text-2xl font-bold text-gray-800">{{ auth()->user()->notifications()->unread()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification List --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            <div 
                class="border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}"
                x-data="{ notif: @js($notification) }"
            >
                <div class="p-4 flex gap-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $notification->is_read ? 'bg-gray-200 text-gray-600' : 'bg-blue-500 text-white' }}">
                            <i class="fas fa-{{ $notification->icon }}"></i>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $notification->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->time_ago }}
                                </p>
                            </div>
                            
                            {{-- Actions --}}
                            <div class="flex gap-2">
                                @if(!$notification->is_read)
                                    <button 
                                        @click="markAsRead({{ $notification->id }})"
                                        class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs font-medium"
                                    >
                                        <i class="fas fa-check mr-1"></i>Tandai Dibaca
                                    </button>
                                @endif
                                
                                @if($notification->link)
                                    <a 
                                        href="{{ $notification->link }}"
                                        onclick="markAsRead({{ $notification->id }})"
                                        class="px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-xs font-medium"
                                    >
                                        <i class="fas fa-arrow-right mr-1"></i>Lihat
                                    </a>
                                @endif
                                
                                <button 
                                    @click="deleteNotification({{ $notification->id }})"
                                    class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-xs font-medium"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Tidak ada notifikasi</p>
                <p class="text-sm text-gray-400 mt-2">Notifikasi akan muncul di sini ketika ada aktivitas baru</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function notificationPage() {
    return {
        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        async markAllAsRead() {
            if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) return;
            
            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        async deleteNotification(id) {
            if (!confirm('Hapus notifikasi ini?')) return;
            
            try {
                const response = await fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        },

        async deleteAllRead() {
            if (!confirm('Hapus semua notifikasi yang sudah dibaca?')) return;
            
            try {
                const response = await fetch('/notifications/delete-read', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }
    }
}
</script>
@endsection