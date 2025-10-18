<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiKomPu - Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f7f8fa; }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #1b2a4e;
            color: white;
            padding-top: 20px;
        }
        .sidebar img {
            width: 80px;
            margin: 0 auto;
            display: block;
        }
        .sidebar h5 {
            text-align: center;
            margin-top: 10px;
            font-weight: 600;
        }
        .sidebar a {
            color: #cfd8e3;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: #0d6efd;
            color: white;
            border-radius: 8px;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .topbar {
            background-color: white;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <img src="{{ asset('images/logo.sikompu.png') }}" alt="Logo SiKomPu">
        <h5>SiKomPu</h5>
        <a href="{{ route('dosen.dashboard') }}" class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dasbor
        </a>
        <a href="{{ route('self.index') }}" class="{{ request()->routeIs('self.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> Self Assessment
        </a>
        <a href="{{ route('dosen.sertifikat') }}" class="{{ request()->routeIs('dosen.sertifikat') ? 'active' : '' }}">
            <i class="bi bi-award"></i> Sertifikat
        </a>
        <a href="{{ route('dosen.penelitian') }}" class="{{ request()->routeIs('dosen.penelitian') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Penelitian
        </a>
        <a href="
