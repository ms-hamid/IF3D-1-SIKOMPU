<section class="bg-white rounded-x shadow-sm p-6 flex flex-col lg:flex-row items-start gap-8">
  {{-- Foto Dosen --}}
  <div class="w-48 h-48 sm:w-56 sm:h-56 lg:w-64 lg:h-64 mx-auto lg:mx-0">
    <img src="{{ asset('images/foto_dosen.jpeg') }}" alt="Foto Dosen"
         class="rounded-x w-full h-full object-cover">
  </div>

  {{-- Data Diri --}}
  <div class="flex-1">
    <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wide mb-2">Data Diri Dosen</h2>
    <hr class="border-gray-300 mb-3">

    <table class="text-sm text-gray-700 w-full max-w-lg">
      <tr><td class="pr-3 py-1 font-medium">Nama</td><td class="pr-2">:</td><td>Dr. Mega Sari</td></tr>
      <tr><td class="pr-3 py-1 font-medium">NIDN</td><td class="pr-2">:</td><td>1122334455</td></tr>
      <tr><td class="pr-3 py-1 font-medium">Program Studi</td><td class="pr-2">:</td><td>Teknik Informatika</td></tr>
      <tr><td class="pr-3 py-1 font-medium">Email</td><td class="pr-2">:</td><td>mega.sari@polibatam.ac.id</td></tr>
      <tr>
        <td class="pr-3 py-1 font-medium">Status</td>
        <td class="pr-2">:</td>
        <td>
          <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">
            Sudah Isi Self-Assessment
          </span>
        </td>
      </tr>
    </table>
  </div>
</section>

