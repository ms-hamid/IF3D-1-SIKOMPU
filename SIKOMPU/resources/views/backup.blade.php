from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import joblib # Library untuk memanggil file .pkl
import os
import requests 

app = Flask(__name__)
LARAVEL_STORE_URL = "http://127.0.0.1:8000/api/ai/hasil-rekomendasi"

# ==========================================
# 1. LOAD MODEL & SCALER (Hanya sekali saat start)
# ==========================================
MODEL_PATH = 'xgb_model.pkl'
SCALER_PATH = 'scaler.pkl'
ENCODER_PATH = 'label_encoders.pkl'

print("--- Memuat Model AI... ---")

# Cek apakah file ada
if os.path.exists(MODEL_PATH) and os.path.exists(SCALER_PATH):
    try:
        # Inilah cara memanggil file .pkl
        model = joblib.load(MODEL_PATH)
        scaler = joblib.load(SCALER_PATH)
        encoders = joblib.load(ENCODER_PATH)

        le_dosen = encoders["Nama Dosen"]
        le_matkul = encoders["Kode_Matkul"]
        
        print(f"SUKSES: {MODEL_PATH} dan {SCALER_PATH} berhasil dimuat.")
        model_ready = True
    except Exception as e:
        print(f"ERROR: Gagal memuat model. {str(e)}")
        model_ready = False
else:
    print("WARNING: File model/scaler tidak ditemukan. Pastikan sudah training!")
    model_ready = False

# ==========================================
# 2. ROUTES
# ==========================================

@app.route('/', methods=['GET'])
def home():
    status = "Ready" if model_ready else "Not Ready (Model Missing)"
    return jsonify({
        "service": "SIKOMPU AI Engine (XGBoost)",
        "status": status
    }), 200

@app.route('/api/predict', methods=['POST'])
def predict():
    # Cek kesiapan model
    if not model_ready:
        return jsonify({"status": "error", "message": "Model AI belum siap di server."}), 500

    try:
        # A. Terima Data JSON dari Laravel
        input_data = request.get_json()
        
        if not input_data or 'dosen' not in input_data:
            return jsonify({"status": "error", "message": "Data dosen tidak ditemukan"}), 400

        # B. Konversi ke DataFrame
        df = pd.DataFrame(input_data['dosen'])

        # pastikan ada kolom nama & kode_matkul (yang diperlukan untuk encoding)
        if 'nama' not in df.columns or 'kode_matkul' not in df.columns:
            return jsonify({"status":"error","message":"Field 'nama' dan/atau 'kode_matkul' wajib ada di input"}), 400

        # ==========================================
        # MAPPING NAMA KOLOM API -> KOLOM MODEL
        # ==========================================
        df.rename(columns={
            'c1_self_assessment': 'Self_Assessment',
            'c2_pendidikan': 'Riwayat_Pendidikan',
            'c3_sertifikat': 'Sertifikasi',
            'c4_penelitian': 'Riwayat_Penelitian'
        }, inplace=True)

        # Simpan nama asli agar bisa dikembalikan apa adanya di response
        df['nama_asli'] = df['nama']

        # ==========================================
        # SAFE ENCODE untuk Nama Dosen & Kode Matkul
        # ==========================================
        def safe_encode(encoder, value):
            try:
                return int(encoder.transform([value])[0])
            except Exception:
                # fallback jika value baru / tidak ada di encoder
                print(f"⚠ WARNING: value '{value}' not found in encoder, using 0")
                return 0

        # Buat kolom encoded terpisah (supaya nama asli tetap tersisa)
        df['Nama_Dosen_Enc'] = df['nama'].apply(lambda x: safe_encode(le_dosen, x))
        df['Kode_Matkul_Enc'] = df['kode_matkul'].apply(lambda x: safe_encode(le_matkul, x))

        # ==========================================
        # Pastikan kolom numeric sesuai training tersedia
        # ==========================================
        numeric_cols = ['Self_Assessment', 'Riwayat_Pendidikan', 'Sertifikasi', 'Riwayat_Penelitian']
        if not all(col in df.columns for col in numeric_cols):
            return jsonify({"status":"error","message":f"Kolom numeric tidak lengkap. Wajib ada: {numeric_cols}"}), 400

        # C. Preprocessing (Scaling) — hanya numeric_cols
        numeric_array = df[numeric_cols].values
        numeric_scaled = scaler.transform(numeric_array)   # shape = (n_rows, 4)

        # D. Gabungkan kategori(2) + numeric_scaled(4) => final_features (n_rows, 6)
        categorical_array = df[['Nama_Dosen_Enc', 'Kode_Matkul_Enc']].values  # shape = (n_rows,2)
        final_features = np.hstack([categorical_array, numeric_scaled])      # shape = (n_rows,6)

        # E. Prediksi Menggunakan Model XGBoost (expect 6 fitur)
        probabilitas_sukses = model.predict_proba(final_features)[:, 1]

        # F. Masukkan Hasil ke DataFrame & Ranking
        # F. Masukkan hasil prediksi ke DF
        df['skor_prediksi'] = probabilitas_sukses

        # ===== Ranking per mata kuliah (AMAN) =====
        hasil_per_matkul = []

        # pastikan groupby pakai kolom yang benar
        if 'kode_matkul' not in df.columns:
            return jsonify({"status": "error", "message": "Kolom kode_matkul tidak ditemukan"}), 400
        
        for mk, group in df.groupby("kode_matkul"):
            if len(group) == 0:
                continue  # skip dataset kosong

            # urutkan nilai
            group = group.sort_values(by="skor_prediksi", ascending=False).reset_index(drop=True)

            # tambahkan kolom rank
            group["rank"] = group.index + 1

            hasil_per_matkul.append(group)

        # jika sama sekali tidak ada group
        if len(hasil_per_matkul) == 0:
            return jsonify({"status": "error", "message": "Tidak ada data untuk diranking"}), 400

        # gabungkan lagi
        df_final = pd.concat(hasil_per_matkul, ignore_index=True)
        
        # ==========================================
        # KIRIM HASIL REKOMENDASI KE LARAVEL
        # ==========================================

        payload_laravel = {
            "semester": input_data.get("semester", "Ganjil 2024/2025"),
            "rekomendasi": []
        }

        for mk, group in df_final.groupby("kode_matkul"):
            rekom_mk = {
                "matakuliah_id": int(mk),  # HARUS ID mata_kuliahs di DB Laravel
                "dosens": []
            }

            for _, row in group.iterrows():
                rekom_mk["dosens"].append({
                    "user_id": int(row["id"]),        # HARUS user_id Laravel
                    "skor": float(row["skor_prediksi"])
                })

            payload_laravel["rekomendasi"].append(rekom_mk)

        try:
            response = requests.post(
                LARAVEL_STORE_URL,
                json=payload_laravel,
                timeout=5
            )
            print("POST ke Laravel:", response.status_code, response.text)
        except Exception as e:
            print("GAGAL POST ke Laravel:", str(e))


        # pilih kolom yang aman
        required_cols = ['id', 'nama_asli', 'kode_matkul', 'skor_prediksi', 'rank']

        missing = [c for c in required_cols if c not in df_final.columns]
        if missing:
            return jsonify({"status": "error", "message": f"Kolom hilang: {missing}"}), 500     

        # Pilih kolom yang ingin dikembalikan (pakai nama asli)
        hasil = df_final[required_cols].rename(columns={'nama_asli': 'nama'}).to_dict(orient='records')

        return jsonify({
            "status": "success",
            "total_data": len(hasil),
            "hasil_rekomendasi": hasil
        }), 200


    except Exception as e:
        return jsonify({"status": "error", "message": f"Internal Error: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
    
    
    
    
    
    
   
   
    
    
#PERUBAHAN DARI VERSI HAMID  

# from flask import Flask, request, jsonify
# import pandas as pd
# import numpy as np
# import joblib # Library untuk memanggil file .pkl
# import os

# app = Flask(__name__)

# # ==========================================
# # 1. LOAD MODEL & SCALER (Hanya sekali saat start)
# # ==========================================
# MODEL_PATH = 'xgboost_model.pkl'
# SCALER_PATH = 'scaler.pkl'

# print("--- Memuat Model AI... ---")

# # Cek apakah file ada
# if os.path.exists(MODEL_PATH) and os.path.exists(SCALER_PATH):
#     try:
#         # Inilah cara memanggil file .pkl
#         model = joblib.load(MODEL_PATH)
#         scaler = joblib.load(SCALER_PATH)
#         print(f"SUKSES: {MODEL_PATH} dan {SCALER_PATH} berhasil dimuat.")
#         model_ready = True
#     except Exception as e:
#         print(f"ERROR: Gagal memuat model. {str(e)}")
#         model_ready = False
# else:
#     print("WARNING: File model/scaler tidak ditemukan. Pastikan sudah training!")
#     model_ready = False

# # ==========================================
# # 2. ROUTES
# # ==========================================

# @app.route('/', methods=['GET'])
# def home():
#     status = "Ready" if model_ready else "Not Ready (Model Missing)"
#     return jsonify({
#         "service": "SIKOMPU AI Engine (XGBoost)",
#         "status": status
#     }), 200

# @app.route('/api/predict', methods=['POST'])
# def predict():
#     # Cek kesiapan model
#     if not model_ready:
#         return jsonify({"status": "error", "message": "Model AI belum siap di server."}), 500

#     try:
#         # A. Terima Data JSON dari Laravel
#         input_data = request.get_json()
        
#         if not input_data or 'dosen' not in input_data:
#             return jsonify({"status": "error", "message": "Data dosen tidak ditemukan"}), 400

#         # B. Konversi ke DataFrame
#         df = pd.DataFrame(input_data['dosen'])
        
#         # Pastikan kolom sesuai dengan urutan saat Training!
#         # (Sesuaikan nama kolom ini dengan JSON dari Laravel Anda)
#         feature_cols = ['c1_self_assessment', 'c2_pendidikan', 'c3_sertifikat', 'c4_penelitian']
        
#         # Cek kelengkapan kolom
#         if not all(col in df.columns for col in feature_cols):
#             return jsonify({"status": "error", "message": f"Kolom data tidak lengkap. Wajib ada: {feature_cols}"}), 400

#         # Ambil hanya data fitur (X)
#         X_input = df[feature_cols]

#         # C. Preprocessing (Scaling)
#         # Wajib menggunakan scaler yang sama dengan saat training
#         X_scaled = scaler.transform(X_input)
        
#         # D. Prediksi Menggunakan Model XGBoost
#         # predict_proba mengembalikan array: [[prob_gagal, prob_sukses], ...]
#         # Kita ambil indeks [:, 1] yaitu probabilitas SUKSES (Kelas 1)
#         probabilitas_sukses = model.predict_proba(X_scaled)[:, 1]
        
#         # E. Masukkan Hasil ke DataFrame
#         df['skor_prediksi'] = probabilitas_sukses
        
#         # F. Ranking & Formatting (Urutkan dari skor tertinggi)
#         df = df.sort_values(by='skor_prediksi', ascending=False)
#         df['rank'] = range(1, len(df) + 1)
        
#         # Format skor menjadi persen atau desimal (opsional)
#         # df['skor_prediksi'] = df['skor_prediksi'].round(4)
        
#         # Pilih kolom untuk dikembalikan ke Laravel
#         hasil = df[['id', 'nama', 'skor_prediksi', 'rank']].to_dict(orient='records')

#         # G. Kirim Balik ke Laravel
#         return jsonify({
#             "status": "success",
#             "total_data": len(hasil),
#             "hasil_rekomendasi": hasil
#         }), 200

#     except Exception as e:
#         return jsonify({"status": "error", "message": f"Internal Error: {str(e)}"}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5000, debug=True)




# VERSI HAMID

# from flask import Flask, request, jsonify
# import pandas as pd
# import numpy as np
# import joblib # Library untuk memanggil file .pkl
# import os

# app = Flask(__name__)

# # ==========================================
# # 1. LOAD MODEL & SCALER (Hanya sekali saat start)
# # ==========================================
# MODEL_PATH = 'xgboost_model.pkl'
# SCALER_PATH = 'scaler.pkl'

# print("--- Memuat Model AI... ---")

# # Cek apakah file ada
# if os.path.exists(MODEL_PATH) and os.path.exists(SCALER_PATH):
#     try:
#         # Inilah cara memanggil file .pkl
#         model = joblib.load(MODEL_PATH)
#         scaler = joblib.load(SCALER_PATH)
#         print(f"SUKSES: {MODEL_PATH} dan {SCALER_PATH} berhasil dimuat.")
#         model_ready = True
#     except Exception as e:
#         print(f"ERROR: Gagal memuat model. {str(e)}")
#         model_ready = False
# else:
#     print("WARNING: File model/scaler tidak ditemukan. Pastikan sudah training!")
#     model_ready = False

# # ==========================================
# # 2. ROUTES
# # ==========================================

# @app.route('/', methods=['GET'])
# def home():
#     status = "Ready" if model_ready else "Not Ready (Model Missing)"
#     return jsonify({
#         "service": "SIKOMPU AI Engine (XGBoost)",
#         "status": status
#     }), 200

# @app.route('/api/predict', methods=['POST'])
# def predict():
#     # Cek kesiapan model
#     if not model_ready:
#         return jsonify({"status": "error", "message": "Model AI belum siap di server."}), 500

#     try:
#         # A. Terima Data JSON dari Laravel
#         input_data = request.get_json()
        
#         if not input_data or 'dosen' not in input_data:
#             return jsonify({"status": "error", "message": "Data dosen tidak ditemukan"}), 400

#         # B. Konversi ke DataFrame
#         df = pd.DataFrame(input_data['dosen'])
        
#         # Pastikan kolom sesuai dengan urutan saat Training!
#         # (Sesuaikan nama kolom ini dengan JSON dari Laravel Anda)
#         feature_cols = ['c1_self_assessment', 'c2_pendidikan', 'c3_sertifikat', 'c4_penelitian']
        
#         # Cek kelengkapan kolom
#         if not all(col in df.columns for col in feature_cols):
#             return jsonify({"status": "error", "message": f"Kolom data tidak lengkap. Wajib ada: {feature_cols}"}), 400

#         # Ambil hanya data fitur (X)
#         X_input = df[feature_cols]

#         # C. Preprocessing (Scaling)
#         # Wajib menggunakan scaler yang sama dengan saat training
#         X_scaled = scaler.transform(X_input)
        
#         # D. Prediksi Menggunakan Model XGBoost
#         # predict_proba mengembalikan array: [[prob_gagal, prob_sukses], ...]
#         # Kita ambil indeks [:, 1] yaitu probabilitas SUKSES (Kelas 1)
#         probabilitas_sukses = model.predict_proba(X_scaled)[:, 1]
        
#         # E. Masukkan Hasil ke DataFrame
#         df['skor_prediksi'] = probabilitas_sukses
        
#         # F. Ranking & Formatting (Urutkan dari skor tertinggi)
#         df = df.sort_values(by='skor_prediksi', ascending=False)
#         df['rank'] = range(1, len(df) + 1)
        
#         # Format skor menjadi persen atau desimal (opsional)
#         # df['skor_prediksi'] = df['skor_prediksi'].round(4)
        
#         # Pilih kolom untuk dikembalikan ke Laravel
#         hasil = df[['id', 'nama', 'skor_prediksi', 'rank']].to_dict(orient='records')

#         # G. Kirim Balik ke Laravel
#         return jsonify({
#             "status": "success",
#             "total_data": len(hasil),
#             "hasil_rekomendasi": hasil
#         }), 200

#     except Exception as e:
#         return jsonify({"status": "error", "message": f"Internal Error: {str(e)}"}), 500

# if __name__ == '__main__':
#     app.run(host='0.0.0.0', port=5000, debug=True)

<!-- <!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Overlay untuk mobile --}}
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-black bg-opacity-25 z-20 md:hidden">
    </div>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        
        {{-- Header --}}
        <header class="flex items-center justify-between bg-white shadow p-4 border-b border-gray-200">
            <div class="flex items-center">
                <button class="md:hidden mr-4" @click="sidebarOpen = true">
                    ☰
                </button>
                <h2 class="text-xl font-semibold">@yield('pageTitle', 'Dashboard')</h2>
            </div>
            <div>
                <span class="hidden sm:inline">Welcome, {{ Auth::user()->name ?? 'User' }}</span>
            </div>
        </header>

        {{-- Halaman Konten --}}
        <main class="flex-1 overflow-auto">
            @yield('content')
        </main>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html> -->








<!-- <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'SIKOMPU')</title>

  <style>
    [x-cloak] { display: none !important; }
  </style>

  {{-- Tailwind CSS CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Bootstrap (opsional) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">

  {{-- Sidebar --}}
  <aside class="w-64 bg-white border-r border-gray-200 fixed top-0 left-0 h-screen overflow-y-auto z-30">
    <x-sidebar />
  </aside>

  {{-- Main content wrapper --}}
  <div class="flex-1 ml-64 flex flex-col">
    
    {{-- Header --}}
    <x-navbar />
  

    {{-- Konten halaman --}}
    <main class="flex-1 overflow-y-auto p-8">
      {{-- Alert sukses --}}
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html> -->


<!-- @extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<main class="flex-1 p-6">

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white rounded-2xl p-5 flex justify-between items-center mb-6">
    <div>
      <h3 class="font-semibold text-lg">
        Sistem Penentuan Koordinator & Pengampu Dosen
      </h3>
      <p class="text-sm text-blue-100 mb-3">
        Kelola dan optimalkan distribusi beban mengajar dosen dengan algoritma cerdas
      </p>
      <button class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50">
        Generate Rekomendasi Semester Ini
      </button>
    </div>
    <img src="{{ asset('images/div.png') }}" alt="Robot Icon" class="w-16 h-16 object-contain">
  </div>

  {{-- Greeting --}}
  <div class="w-full border-b border-gray-300 pb-3 mb-6">
    <div class="flex items-center space-x-3">
      <div class="bg-green-100 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line text-green-600"></i>
      </div>
      <div class="flex flex-col">
        <h3 class="text-lg font-semibold text-gray-800">
          Selamat Datang, {{ auth()->user()->name ?? 'Nama Dosen' }}
        </h3>
        <p class="text-gray-500 text-sm mt-1 flex items-center">
          <i class="fa-regular fa-calendar text-gray-400 mr-1"></i>
          Minggu, 28 September 2025
        </p>
      </div>
    </div>
  </div>

  {{-- Card Data Diri Dosen --}}
  <div class="bg-white border rounded p-6 flex flex-col lg:flex-row items-start gap-8 mb-6">
    <div class="w-64 h-64 flex-shrink-0 mx-auto lg:mx-0">
      <img src="{{ asset('images/foto-dosen.png') }}" alt="Foto Dosen" class="rounded-xl w-full h-full object-cover border">
    </div>

    <div class="flex-1">
      <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wide mb-2">Data Diri Dosen</h2>
      <hr class="border-gray-300 mb-3">

      <table class="text-sm text-gray-700">
        <tr><td class="pr-3 py-1 font-medium">Nama</td><td class="pr-2">:</td><td>Dr. Mega Sari</td></tr>
        <tr><td class="pr-3 py-1 font-medium">NIDN</td><td class="pr-2">:</td><td>1122334455</td></tr>
        <tr><td class="pr-3 py-1 font-medium">Program Studi</td><td class="pr-2">:</td><td>Teknik Informatika</td></tr>
        <tr><td class="pr-3 py-1 font-medium">Email</td><td class="pr-2">:</td><td>mega.sari@polibatam.ac.id</td></tr>
        <tr>
          <td class="pr-3 py-1 font-medium">Status</td><td class="pr-2">:</td>
          <td><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Sudah Isi Self-Assessment</span></td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Card Gabungan: Diagram (Kiri) + Aktivitas (Kanan) --}}
  <div class="bg-white border rounded p-6 flex flex-col lg:flex-row gap-6 shadow-sm">

    {{-- Diagram Self-Assessment --}}
    <div class="flex flex-col items-center justify-center flex-1 border-b lg:border-b-0 lg:border-r border-gray-200 pb-6 lg:pb-0 lg:pr-6">
      <h4 class="font-semibold text-gray-700 mb-4 text-center">Rata-Rata Dosen Self-Assessment</h4>
      <div class="relative w-40 h-40">
        <svg class="w-full h-full">
          <circle cx="50%" cy="50%" r="70" stroke="#e5e7eb" stroke-width="10" fill="none" />
          <circle cx="50%" cy="50%" r="70" stroke="#2563eb" stroke-width="10" fill="none"
                  stroke-dasharray="440" stroke-dashoffset="88"
                  stroke-linecap="round" transform="rotate(-90 80 80)" />
        </svg>
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="text-2xl font-bold text-gray-800">80%</span>
        </div>
      </div>
      <div class="flex justify-center mt-3 text-sm text-gray-500">
        <span class="flex items-center mr-4"><span class="w-3 h-3 bg-blue-600 rounded-full mr-1"></span> Sudah Mengisi</span>
        <span class="flex items-center"><span class="w-3 h-3 bg-gray-200 rounded-full mr-1"></span> Belum Mengisi</span>
      </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="flex-1">
      <div class="flex items-center justify-between mb-4">
        <h4 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru Anda</h4>
        <a href="#" class="text-blue-600 text-sm hover:underline">Lihat semua</a>
      </div>

      <ul class="space-y-4">
        <li class="relative border-l-4 border-blue-500 bg-blue-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Perbarui Self-Assessment</h5>
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-md font-medium">Selesai</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Mata kuliah Algoritma dan Pemrograman</p>
          <p class="text-xs text-gray-400 mt-2 text-right">2 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-green-500 bg-green-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Unggah Sertifikat Baru</h5>
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-md font-medium">Tersimpan</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">“Certified Scrum Master”</p>
          <p class="text-xs text-gray-400 mt-2 text-right">5 jam yang lalu</p>
        </li>

        <li class="relative border-l-4 border-yellow-500 bg-yellow-50/40 rounded-xl p-4 hover:shadow transition">
          <div class="flex justify-between items-start">
            <h5 class="font-semibold text-gray-800">Generate Rekomendasi</h5>
            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-md font-medium">Proses</span>
          </div>
          <p class="text-sm text-gray-600 mt-1">Semester Genap 2024/2025 telah selesai</p>
          <p class="text-xs text-gray-400 mt-2 text-right">1 hari yang lalu</p>
        </li>
      </ul>
    </div>
  </div>

</main>
@endsection -->


<!-- Sidebar

<aside 
  class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200 shadow-sm transform 
         -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
  :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
>
  <div class="flex flex-col h-full justify-between px-3 pt-3 pb-5 overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center mb-5 space-x-3 pl-1">
      <img src="{{ asset('images/logo_sikompu.png') }}" alt="Logo SiKompu" class="w-14 h-14 object-contain -ml-1">
      <div>
        <h1 class="text-lg font-bold text-[#1E3A8A] leading-tight">SIKOMPU</h1>
        <p class="text-[11px] text-gray-600 leading-tight">SISTEM PENENTUAN<br>KOORDINATOR PENGAMPU</p>
      </div>
    </div>

    <div class="border-t border-gray-200 mb-3"></div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-1 text-[15px]">
      @php
        $menus = [
          ['route' => 'dashboard.dosen', 'icon' => 'fa-solid fa-gauge', 'label' => 'Dashboard'],
          ['route' => 'self-assessment.index', 'icon' => 'fa-regular fa-square-check', 'label' => 'Self Assessment'],
          ['route' => 'sertifikasi.index', 'icon' => 'fa-solid fa-id-card', 'label' => 'Sertifikasi'],
          ['route' => 'penelitian.index', 'icon' => 'fa-solid fa-flask', 'label' => 'Penelitian'],
          ['route' => 'laporan.index', 'icon' => 'fa-regular fa-file-lines', 'label' => 'Laporan'],
        ];
      @endphp

      @foreach ($menus as $menu)
        @php
          $isActive = request()->routeIs(Str::before($menu['route'], '.') . '.*');
        @endphp

        <a href="{{ route($menu['route']) }}"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition-all duration-300
          {{ $isActive 
              ? 'bg-gradient-to-br from-[#1E3A8A] to-[#1E40AF] text-white' 
              : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700 active:bg-blue-50 focus:bg-blue-50 focus:text-blue-700' }}">
          
          <div class="flex items-center justify-center w-8 h-8 rounded-md 
                      {{ $isActive ? 'bg-white text-blue-900' : 'bg- text-gray-500 group-hover:bg-blue-600 group-hover:text-white' }}
                      transition-colors duration-300">
            <i class="{{ $menu['icon'] }} text-sm"></i>
          </div>

          <span>{{ $menu['label'] }}</span>
        </a>
      @endforeach
    </nav>

  </div>
</aside> -->



