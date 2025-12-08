<!-- resources/views/components/form-tambah-dosen.blade.php -->

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<form action="{{ url('/tambah.mata.kuliah') }}" method="POST" class="space-y-5">
   @csrf

<!-- Optional JS untuk Reset Password -->
<script>
document.getElementById('resetPassword').addEventListener('click', function () {
   document.getElementById('password').value = '';
});
</script>
