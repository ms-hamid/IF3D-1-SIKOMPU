from flask import Flask, request, jsonify
from ahp_saw import hitung_bobot_ahp, normalisasi_saw, hitung_saw
import pandas as pd
import numpy as np
import os

app = Flask(__name__)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATA_DIR = os.path.join(BASE_DIR, "data-dummy")

@app.route('/')
def index():
    return jsonify({"message": "API AHP–SAW SIKOMPU aktif dan siap baca file Excel dari data-dummy"})

@app.route('/hitung-ahp-saw', methods=['POST'])
def hitung_ahp_saw():
    try:
        data = request.get_json()
        if not data or "matrix_ahp" not in data:
            return jsonify({"error": "matrix_ahp wajib dikirim"}), 400

        matrix_ahp = np.array(data["matrix_ahp"])
        bobot = hitung_bobot_ahp(matrix_ahp)

        # ===============================
        # 1 Baca Data Dummy
        # ===============================
        file_self = os.path.join(DATA_DIR, "Data_Dummy_Self_Assessment_IF.xlsx")
        file_sertif = os.path.join(DATA_DIR, "data_dummy_sertifikasi_dosen.xlsx")
        file_penelitian = os.path.join(DATA_DIR, "data_dummy_penelitian_dosen.xlsx")

        df_self = pd.read_excel(file_self, header=0)
        df_sertif = pd.read_excel(file_sertif, header=0)
        df_penelitian = pd.read_excel(file_penelitian, header=0)

        # Bersihkan nama kolom
        df_self.columns = df_self.columns.str.strip()
        df_sertif.columns = df_sertif.columns.str.strip()
        df_penelitian.columns = df_penelitian.columns.str.strip()

        # ===============================
        # 2 Sesuaikan kolom penting
        # ===============================
        # Self Assessment
        if "Self_Assessment" not in df_self.columns:
            # jika kolom berbeda, pilih kolom selain Nama_Dosen, NIDN, Prodi
            possible = [c for c in df_self.columns if c not in ["Nama_Dosen","NIDN","Prodi Asal","prodi_asal"]]
            df_self["Self_Assessment"] = df_self[possible].sum(axis=1)
        
        # Sertifikasi
        if "Sertifikat_Kompetensi" not in df_sertif.columns:
            # contoh kolom Excel sertifikasi: nama_sertifikat
            for col in df_sertif.columns:
                if "nama_sertifikat" in col.lower() or "sertifikat" in col.lower():
                    df_sertif.rename(columns={col:"Sertifikat_Kompetensi"}, inplace=True)
                    break

        # Penelitian
        if "Riwayat_Penelitian" not in df_penelitian.columns:
            for col in df_penelitian.columns:
                if "penelitian" in col.lower():
                    df_penelitian.rename(columns={col:"Riwayat_Penelitian"}, inplace=True)
                    break

        # Pastikan Nama_Dosen ada
        for df_ in [df_self, df_sertif, df_penelitian]:
            if "Nama_Dosen" not in df_.columns:
                possible = [c for c in df_.columns if "dosen" in c.lower()]
                df_.rename(columns={possible[0]:"Nama_Dosen"}, inplace=True)

        # ===============================
        # 3 Merge DataFrames
        # ===============================
        df = df_self[['Nama_Dosen','Self_Assessment']].merge(
            df_sertif[['Nama_Dosen','Sertifikat_Kompetensi']], on='Nama_Dosen', how='left'
        ).merge(
            df_penelitian[['Nama_Dosen','Riwayat_Penelitian']], on='Nama_Dosen', how='left'
        )

        # Isi NaN dengan 0
        df[['Self_Assessment','Sertifikat_Kompetensi','Riwayat_Penelitian']] = df[['Self_Assessment','Sertifikat_Kompetensi','Riwayat_Penelitian']].fillna(0)

        # ===============================
        # 4 Normalisasi + SAW
        # ===============================
        matrix = df[['Self_Assessment','Sertifikat_Kompetensi','Riwayat_Penelitian']].values
        matrix_norm = normalisasi_saw(matrix)
        nilai_akhir = hitung_saw(matrix_norm, bobot)

        df['Nilai_Akhir'] = nilai_akhir
        df = df.sort_values(by='Nilai_Akhir', ascending=False).reset_index(drop=True)
        df['Ranking'] = df.index + 1

        return jsonify({
            "bobot_kriteria": {
                "Self_Assessment": round(float(bobot[0]),4),
                "Sertifikat_Kompetensi": round(float(bobot[1]),4),
                "Riwayat_Penelitian": round(float(bobot[2]),4)
            },
            "hasil_ranking": df.to_dict(orient='records')
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, port=5000)
