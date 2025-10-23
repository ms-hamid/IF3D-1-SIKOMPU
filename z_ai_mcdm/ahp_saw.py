import numpy as np

# ======================================================
# FUNGSI 1: Hitung Bobot AHP
# ======================================================
def hitung_bobot_ahp(matrix):
    """
    Menghitung bobot kriteria menggunakan metode AHP
    -----------------------------------------------
    Input:
      - matrix : matriks perbandingan berpasangan (NxN)
                 Contoh:
                 [[1, 3, 2],
                  [1/3, 1, 0.5],
                  [0.5, 2, 1]]

    Output:
      - bobot normalisasi (dalam bentuk array 1D)
    """
    matrix = np.array(matrix, dtype=float)

    # Hitung eigenvalue dan eigenvector
    eigvals, eigvecs = np.linalg.eig(matrix)

    # Ambil eigenvector dari eigenvalue terbesar
    max_index = np.argmax(eigvals)
    eigvec = np.real(eigvecs[:, max_index])

    # Normalisasi bobot agar total = 1
    bobot = eigvec / np.sum(eigvec)

    # Pastikan hasilnya positif dan dibulatkan
    bobot = np.abs(bobot)
    return np.round(bobot / np.sum(bobot), 4)


# ======================================================
# FUNGSI 2: Normalisasi Matriks SAW
# ======================================================
def normalisasi_saw(matrix, jenis_kriteria=None):
    """
    Melakukan normalisasi matriks keputusan.
    ---------------------------------------
    Rumus:
      Benefit:  R_ij = X_ij / X_max(j)
      Cost:     R_ij = X_min(j) / X_ij

    Input:
      - matrix : matriks keputusan (alternatif x kriteria)
      - jenis_kriteria : list dengan nilai 'benefit' atau 'cost' (opsional)
        Default semua dianggap benefit.
    """
    matrix = np.array(matrix, dtype=float)
    n_kriteria = matrix.shape[1]

    if jenis_kriteria is None:
        jenis_kriteria = ['benefit'] * n_kriteria

    norm = np.zeros_like(matrix, dtype=float)

    for j in range(n_kriteria):
        if jenis_kriteria[j] == 'benefit':
            norm[:, j] = matrix[:, j] / np.max(matrix[:, j])
        else:
            norm[:, j] = np.min(matrix[:, j]) / matrix[:, j]

    return np.round(norm, 4)


# ======================================================
# FUNGSI 3: Hitung Nilai Akhir SAW
# ======================================================
def hitung_saw(matrix, bobot):
    """
    Menghitung nilai akhir untuk setiap alternatif (dosen)
    ------------------------------------------------------
    Rumus:
      V_i = Σ (W_j * R_ij)

    Input:
      - matrix : matriks ternormalisasi (alternatif x kriteria)
      - bobot  : bobot kriteria (hasil AHP atau default)
    Output:
      - array nilai akhir (V_i)
    """
    matrix = np.array(matrix, dtype=float)
    bobot = np.array(bobot, dtype=float)

    # Normalisasi bobot agar total tetap 1 (jika belum)
    bobot = bobot / np.sum(bobot)

    nilai = np.dot(matrix, bobot)
    return np.round(nilai, 4)
