<header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between" x-data="notificationDropdown()">

    {{-- Title (Desktop Only) --}}
    <h1 class="hidden lg:block text-xl font-bold text-gray-800">
        @yield('title', 'Dashboard')
    </h1>

    {{-- Right Side: Notification + Profile --}}
    <div class="flex items-center space-x-4 ml-auto">
        
        {{-- Notification Icon dengan Dropdown --}}
        <div class="relative">
            <button 
                @click="toggleDropdown()"
                class="relative text-gray-600 hover:text-gray-900 focus:outline-none">
                <i class="fa-regular fa-bell text-xl"></i>
                
                {{-- Badge Unread Count --}}
                <span 
                    x-show="unreadCount > 0"
                    x-text="unreadCount > 99 ? '99+' : unreadCount"
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold"
                ></span>
            </button>

            {{-- Dropdown Notifikasi --}}
            <div 
                x-show="isOpen"
                @click.away="isOpen = false"
                x-transition
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-[500px] overflow-hidden"
                style="display: none;">
                
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                    <h3 class="font-semibold text-gray-800 text-sm">Notifikasi</h3>
                    <button 
                        @click="markAllAsRead()"
                        x-show="unreadCount > 0"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        Tandai Semua Dibaca
                    </button>
                </div>

                {{-- Notification List --}}
                <div class="overflow-y-auto max-h-[400px]">
                    {{-- Empty State --}}
                    <template x-if="notifications.length === 0">
                        <div class="px-4 py-8 text-center text-gray-500">
                            <i class="fa-regular fa-bell-slash text-4xl mb-2 text-gray-300"></i>
                            <p class="text-sm">Tidak ada notifikasi</p>
                        </div>
                    </template>

                    {{-- Notification Items --}}
                    <template x-for="notif in notifications" :key="notif.id">
                        <div 
                            @click="handleNotificationClick(notif)"
                            :class="notif.is_read ? 'bg-white' : 'bg-blue-50'"
                            class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors relative">
                            
                            <div class="flex gap-3">
                                {{-- Icon --}}
                                <div class="flex-shrink-0">
                                    <div 
                                        :class="notif.is_read ? 'bg-gray-200 text-gray-600' : 'bg-blue-500 text-white'" 
                                        class="w-10 h-10 rounded-full flex items-center justify-center">
                                        <i :class="'fa-solid fa-' + notif.icon"></i>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900" x-text="notif.title"></p>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2" x-text="notif.message"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="notif.time_ago"></p>
                                </div>

                                {{-- Delete Button --}}
                                <div class="flex-shrink-0">
                                    <button 
                                        @click.stop="deleteNotification(notif.id)"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1"
                                        title="Hapus notifikasi">
                                        <i class="fa-solid fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-center text-xs text-gray-500">
                    <span x-text="notifications.length"></span> notifikasi
                </div>
            </div>
        </div>

        {{-- Profile Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center space-x-3 focus:outline-none hover:bg-gray-50 rounded-lg px-3 py-2 transition">
                
                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                    @if(Auth::user()->foto)
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                    @endif
                </div>

                {{-- Name & Role --}}
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ Auth::user()->nama_lengkap }}
                    </p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->jabatan }}</p>
                </div>

                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">
                
                <div class="px-4 py-2 border-b border-gray-200">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->nidn }}</p>
                </div>

                {{-- Link Profil (Gabungan Profil + Ganti Password) --}}
                <a href="{{ route('profil.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fa-solid fa-user mr-3 text-gray-400"></i>
                    Profil Saya
                </a>

                <hr class="my-2">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- Alpine.js Component Logic --}}
<script>
function notificationDropdown() {
    return {
        isOpen: false,
        notifications: [],
        unreadCount: 0,
        isProcessing: false,

        init() {
            console.log('🔔 Notification dropdown initialized');
            this.loadNotifications();
            
            setInterval(() => {
                if (!this.isOpen && !this.isProcessing) {
                    this.loadNotifications();
                }
            }, 30000);
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            console.log('🔔 Dropdown toggled:', this.isOpen);
            if (this.isOpen) {
                this.loadNotifications();
            }
        },

        async loadNotifications() {
            console.log('📥 Loading notifications...');
            try {
                const response = await fetch(`/notifications/get?t=${Date.now()}`, {
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                console.log('✅ Notifications loaded:', {
                    total: this.notifications.length,
                    unread: this.unreadCount
                });
            } catch (error) {
                console.error('❌ Load notifications failed:', error);
            }
        },

        async deleteNotification(id) {
            console.log('🗑️ Delete notification:', id);
            
            if (this.isProcessing) {
                console.log('⚠️ Already processing, skipping...');
                return;
            }
            
            this.isProcessing = true;
            
            // Backup untuk rollback
            const originalNotifications = [...this.notifications];
            const originalUnreadCount = this.unreadCount;
            
            // Update UI instantly
            const targetIndex = this.notifications.findIndex(n => n.id === id);
            if (targetIndex !== -1) {
                const wasUnread = !this.notifications[targetIndex].is_read;
                console.log('📍 Found at index:', targetIndex, '| Was unread:', wasUnread);
                
                this.notifications.splice(targetIndex, 1);
                if (wasUnread) {
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
                console.log('✨ UI updated | Remaining:', this.notifications.length);
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                console.log('🔑 CSRF Token:', csrfToken ? 'Found' : 'NOT FOUND!');
                
                const response = await fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });

                console.log('📡 Server response:', response.status, response.statusText);
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    console.error('❌ Delete failed:', errorData);
                    
                    // Rollback
                    this.notifications = originalNotifications;
                    this.unreadCount = originalUnreadCount;
                    console.log('↩️ Rolled back to original state');
                    
                    alert('Gagal menghapus notifikasi. Cek console untuk detail.');
                } else {
                    const result = await response.json();
                    console.log('✅ Delete success:', result);
                }
            } catch (error) {
                console.error('❌ Delete error:', error);
                
                // Rollback
                this.notifications = originalNotifications;
                this.unreadCount = originalUnreadCount;
                console.log('↩️ Rolled back due to error');
                
                alert('Error: ' + error.message);
            } finally {
                this.isProcessing = false;
                console.log('🏁 Delete process finished');
            }
        },

        async markAllAsRead() {
            console.log('✔️ Mark all as read');
            
            if (this.isProcessing || this.unreadCount === 0) {
                console.log('⚠️ Skipped (processing or no unread)');
                return;
            }
            
            this.isProcessing = true;
            const originalNotifications = JSON.parse(JSON.stringify(this.notifications));
            
            this.notifications.forEach(n => n.is_read = true);
            this.unreadCount = 0;
            console.log('✨ UI updated: all marked as read');

            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    console.error('❌ Mark all as read failed');
                    this.notifications = originalNotifications;
                    this.unreadCount = originalNotifications.filter(n => !n.is_read).length;
                } else {
                    console.log('✅ Mark all as read success');
                }
            } catch (error) {
                console.error('❌ Mark all as read error:', error);
                this.notifications = originalNotifications;
                this.unreadCount = originalNotifications.filter(n => !n.is_read).length;
            } finally {
                this.isProcessing = false;
            }
        },

        handleNotificationClick(notif) {
            console.log('🖱️ Notification clicked:', notif.title);
            if (notif.url) {
                window.location.href = notif.url;
            }
        }
    }
}
</script>