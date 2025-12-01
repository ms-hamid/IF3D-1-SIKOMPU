from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import joblib # Library untuk memanggil file .pkl
import os

app = Flask(__name__)

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