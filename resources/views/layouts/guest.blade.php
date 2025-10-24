<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - SIKOMPU' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF]">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 mx-4">
        @yield('content')
    </div>
</body>
</html>
