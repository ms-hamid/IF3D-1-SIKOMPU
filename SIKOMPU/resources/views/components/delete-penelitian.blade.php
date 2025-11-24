{{-- resources/views/components/delete-penelitian.blade.php --}}

<div class="text-center">
    {{-- Icon Warning --}}
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
        <i class="fa-solid fa-trash-can text-3xl text-red-600"></i>
    </div>

    {{-- Judul --}}
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Hapus Penelitian</h3>
    
    {{-- Deskripsi --}}
    <div class="mb-6">
        <p class="text-sm text-gray-600 mb-3">
            Anda yakin ingin menghapus penelitian ini?
        </p>
        
        {{-- Info Penelitian yang akan dihapus --}}
        <div class="bg-gray-50 rounded-lg p-3 text-left border border-gray-200">
            <p class="text-sm font-medium text-gray-800 mb-1" x-text="deleteData.judul"></p>
            <div class="flex gap-3 text-xs text-gray-600">
                <span><i class="fa-solid fa-calendar mr-1"></i><span x-text="deleteData.tahun"></span></span>
                <span><i class="fa-solid fa-user-tie mr-1"></i><span x-text="deleteData.peran"></span></span>
            </div>
        </div>

        <p class="text-xs text-red-600 mt-3 font-medium">
            <i class="fa-solid fa-circle-exclamation mr-1"></i>
            Data yang dihapus tidak dapat dikembalikan!
        </p>
    </div>

    {{-- Form Hapus --}}
    <form :action="`/penelitian/${deleteData.id}`" method="POST" class="space-y-3">
        @csrf
        @method('DELETE')
        
        <button type="submit"
                class="w-full px-4 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors font-medium">
            <i class="fa-solid fa-trash mr-2"></i>Ya, Hapus Penelitian
        </button>
        
        <button type="button" @click="openDeleteModal = false"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors font-medium">
            Batal
        </button>
    </form>
</div>