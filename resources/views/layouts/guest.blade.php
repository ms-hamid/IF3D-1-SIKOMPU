<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - SIKOMPU' }}</title>

    @vite('resources/css/app.css')

    <!-- Tambahkan baris ini 👇 -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-u2RgN4uK+j3sKhX44hPqE3c9r7DcA6iZf8M2mFf0UAbxv5s8m8v5A4W9QW/8cC6J4t9vXZT2u0yYoG+Tkk0Vdg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF]">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 mx-4">
        @yield('content')
    </div>
</body>
</html>
