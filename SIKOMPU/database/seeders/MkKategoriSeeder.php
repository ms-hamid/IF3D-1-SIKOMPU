<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class MkKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $matkuls = MataKuliah::all();
        $kategoris = Kategori::all();

        foreach ($matkuls as $mk) {
            foreach ($kategoris as $kategori) {

                $bobot = $this->hitungRelevansi($mk->nama_mk, $kategori->nama);

                DB::table('mk_kategori')->updateOrInsert(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'kategori_id'    => $kategori->id,
                    ],
                    [
                        'bobot'      => $bobot,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Hitung skor relevansi otomatis.
     */
    private function hitungRelevansi(string $mkName, string $kategoriName): int
    {
        $mkWords  = $this->normalizeText($mkName);
        $katWords = $this->normalizeText($kategoriName);

        $score = 0;

        // 1. Exact match (10 poin)
        $exact = count(array_intersect($mkWords, $katWords));
        $score += $exact * 10;

        // 2. Synonym match (20 poin)
        $synonyms = $this->synonymDictionary();
        foreach ($mkWords as $w1) {
            foreach ($katWords as $w2) {
                if (isset($synonyms[$w1]) && in_array($w2, $synonyms[$w1])) {
                    $score += 20;
                }
                if (isset($synonyms[$w2]) && in_array($w1, $synonyms[$w2])) {
                    $score += 20;
                }
            }
        }

        // 3. Kemiripan kata (levenshtein)
        foreach ($mkWords as $w1) {
            foreach ($katWords as $w2) {
                $distance = levenshtein($w1, $w2);
                if ($distance <= 2 && min(strlen($w1), strlen($w2)) >= 5) {
                    $score += 15;
                }
            }
        }

        // Konversi ke bobot
        if ($score >= 50) return 5; // sangat relevan
        if ($score >= 20) return 3; // relevan
        return 1; // tidak relevan
    }

    /**
     * Normalisasi teks → pecah kata, buang stopwords
     */
    private function normalizeText(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9 ]/', ' ', $text);

        $words = array_filter(explode(' ', $text));

        $stopwords = ['dan','dasar','lanjut','pengantar','proyek','mata','kuliah','praktikum'];

        return array_values(array_diff($words, $stopwords));
    }

    /**
     * Kamus sinonim sederhana
     */
    private function synonymDictionary()
    {
        return [
            "web" => ["website", "frontend", "backend", "html", "css", "javascript"],
            "pemrograman" => ["programming", "coding", "algoritma"],
            "database" => ["basis", "data", "mysql", "sql"],
            "jaringan" => ["networking", "router", "switch", "tcp", "udp"],
            "komputer" => ["computer", "hardware", "processor"],
            "statistika" => ["statistik", "probabilitas"],
        ];
    }
}
