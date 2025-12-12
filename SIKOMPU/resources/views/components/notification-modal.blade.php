<div 
    x-data="{ open: @json($show), scrollPos: 0 }"
    x-init="$watch('open', value => {
        if(value){
            scrollPos = window.pageYOffset;
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPos}px`;
            document.body.style.left = '0';
            document.body.style.right = '0';
        } else {
            document.body.style.position = '';
            document.body.style.top = '';
            window.scrollTo(0, scrollPos);
        }
    })"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center w-screen h-screen bg-black/30"
    x-cloak
>
    <div 
        class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8 space-y-6 max-h-[90vh] overflow-auto"
    >
        {{-- Header --}}
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fa-solid fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Lengkapi Profil Anda!</h2>
        </div>

        {{-- Pesan --}}
        <p class="text-gray-700 text-base sm:text-lg">
            Untuk keamanan akun dan kelengkapan data, silakan:
            <ul class="list-disc ml-6 mt-2 space-y-1">
                <li>Ganti password default Anda</li>
                <li>Lengkapi data diri dan pendidikan terakhir</li>
            </ul>
        </p>

        {{-- Action Button --}}
        <div class="flex justify-end mt-4">
            <a 
                href="{{ route('profil.index') }}" 
                class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2"
            >
                <i class="fa-solid fa-user-pen"></i>
                Lengkapi Profil
            </a>
        </div>
    </div>
</div>
