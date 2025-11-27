from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import joblib # Library untuk memanggil file .pkl
import os

app = Flask(__name__)

# ==========================================
# 1. LOAD MODEL & SCALER (Hanya sekali saat start)
# ==========================================
MODEL_PATH = 'xgboost_model.pkl'
SCALER_PATH = 'scaler.pkl'

print("--- Memuat Model AI... ---")

# Cek apakah file ada
if os.path.exists(MODEL_PATH) and os.path.exists(SCALER_PATH):
    try:
        # Inilah cara memanggil file .pkl
        model = joblib.load(MODEL_PATH)
        scaler = joblib.load(SCALER_PATH)
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
        
        # Pastikan kolom sesuai dengan urutan saat Training!
        # (Sesuaikan nama kolom ini dengan JSON dari Laravel Anda)
        feature_cols = ['c1_self_assessment', 'c2_pendidikan', 'c3_sertifikat', 'c4_penelitian']
        
        # Cek kelengkapan kolom
        if not all(col in df.columns for col in feature_cols):
            return jsonify({"status": "error", "message": f"Kolom data tidak lengkap. Wajib ada: {feature_cols}"}), 400

        # Ambil hanya data fitur (X)
        X_input = df[feature_cols]

        # C. Preprocessing (Scaling)
        # Wajib menggunakan scaler yang sama dengan saat training
        X_scaled = scaler.transform(X_input)
        
        # D. Prediksi Menggunakan Model XGBoost
        # predict_proba mengembalikan array: [[prob_gagal, prob_sukses], ...]
        # Kita ambil indeks [:, 1] yaitu probabilitas SUKSES (Kelas 1)
        probabilitas_sukses = model.predict_proba(X_scaled)[:, 1]
        
        # E. Masukkan Hasil ke DataFrame
        df['skor_prediksi'] = probabilitas_sukses
        
        # F. Ranking & Formatting (Urutkan dari skor tertinggi)
        df = df.sort_values(by='skor_prediksi', ascending=False)
        df['rank'] = range(1, len(df) + 1)
        
        # Format skor menjadi persen atau desimal (opsional)
        # df['skor_prediksi'] = df['skor_prediksi'].round(4)
        
        # Pilih kolom untuk dikembalikan ke Laravel
        hasil = df[['id', 'nama', 'skor_prediksi', 'rank']].to_dict(orient='records')

        # G. Kirim Balik ke Laravel
        return jsonify({
            "status": "success",
            "total_data": len(hasil),
            "hasil_rekomendasi": hasil
        }), 200

    except Exception as e:
        return jsonify({"status": "error", "message": f"Internal Error: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)