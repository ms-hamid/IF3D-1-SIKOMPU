import pandas as pd

# Baca sheet tertentu dari file pertama (misal self-assessment)
df_tp = pd.read_excel("Data_Dummy_Self_Assessment_IF.xlsx", sheet_name="TP")

# Baca semua sheet dari file sertifikasi
df_sertifikasi = pd.read_excel("data_dummy_sertifikasi_dosen.xlsx", sheet_name="sertifikasi")

# Contoh akses salah satu sheet dari file sertifikasi
print("=== Data Sertifikasi (Sheet TP) ===")
print(df_sertifikasi.head())

# Tampilkan sebagian data dari file self-assessment
print("\n=== Data Self Assessment (TP) ===")
print(df_tp.head())