<?php

namespace App\Services;

class RelevansiService
{
    /**
     * Hitung bobot relevansi Mata Kuliah vs Kategori Sertifikat
     * Output: 1 (tidak relevan), 3 (cukup relevan), 5 (sangat relevan)
     */
    public function hitung(string $mkName, string $kategoriName): int
    {
        $mkWords  = $this->normalize($mkName);
        $katWords = $this->normalize($kategoriName);

        // Jika salah satu kosong → relevansi minimum
        if (empty($mkWords) || empty($katWords)) {
            return 1;
        }

        /* ==================================================
         | 1. DOMAIN MATCH (PRIORITAS UTAMA)
         |    Jika berada di domain keilmuan yang sama → 5
         ================================================== */
        if ($this->domainMatch($mkWords, $katWords)) {
            return 5;
        }

        /* ==================================================
         | 2. EXACT KEYWORD MATCH
         ================================================== */
        if (count(array_intersect($mkWords, $katWords)) > 0) {
            return 3;
        }

        /* ==================================================
         | 3. SIMILAR WORD (TYPO / VARIASI KATA)
         ================================================== */
        foreach ($mkWords as $w1) {
            foreach ($katWords as $w2) {
                if (levenshtein($w1, $w2) <= 2) {
                    return 3;
                }
            }
        }

        return 1;
    }

    /* ==================================================
     | DOMAIN MATCH
     ================================================== */
    private function domainMatch(array $mkWords, array $katWords): bool
    {
        $domains = $this->domainKeywords();

        foreach ($domains as $keywords) {
            if (
                count(array_intersect($mkWords, $keywords)) > 0 &&
                count(array_intersect($katWords, $keywords)) > 0
            ) {
                return true;
            }
        }

        return false;
    }

    /* ==================================================
     | NORMALISASI KATA
     ================================================== */
    private function normalize(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9 ]/', ' ', $text);
        $words = array_filter(explode(' ', $text));

        // Stopword minimum (aman lintas prodi)
        $stopwords = ['dan', 'pengantar', 'mata', 'kuliah', 'dasar'];

        return array_values(array_diff($words, $stopwords));
    }

    /* ==================================================
     | KAMUS DOMAIN KEILMUAN (LINTAS PRODI)
     ================================================== */
    private function domainKeywords(): array
    {
        return [

            // ===== Informatika / RPL =====
            'pemrograman' => [
                'pemrograman', 'programming', 'coding',
                'algoritma', 'software'
            ],

            'web' => [
                'web', 'website', 'frontend', 'backend',
                'html', 'css', 'javascript', 'php'
            ],

            'database' => [
                'database', 'basis', 'data', 'mysql', 'sql'
            ],

            'jaringan' => [
                'jaringan', 'network', 'router',
                'switch', 'iot'
            ],

            'ai' => [
                'ai', 'kecerdasan', 'machine',
                'learning', 'xgboost'
            ],

            // ===== Geomatika =====
            'geospasial' => [
                'geospasial', 'gis', 'peta',
                'arcgis', 'qgis', 'spasial'
            ],

            'survey' => [
                'survey', 'pengukuran', 'topografi',
                'gps', 'gnss'
            ],

            // ===== Animasi =====
            'animasi' => [
                'animasi', 'animation', '3d',
                '2d', 'render', 'visual'
            ],

            'multimedia' => [
                'multimedia', 'audio', 'video',
                'grafis', 'desain'
            ],

            // ===== TRM / Robotika =====
            'robotik' => [
                'robotik', 'robot', 'otomasi',
                'sensor', 'mikrokontroler'
            ],

            'kontrol' => [
                'kontrol', 'kendali', 'arduino',
                'plc'
            ],

            // ===== Game / Permainan =====
            'game' => [
                'game', 'permainan', 'unity',
                'unreal'
            ],

            'interaksi' => [
                'interaksi', 'user', 'ui', 'ux'
            ],
        ];
    }
}
