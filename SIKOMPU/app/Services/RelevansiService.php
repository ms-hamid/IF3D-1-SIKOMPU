<?php

namespace App\Services;

class RelevansiService
{
    public function hitung(string $mkName, string $kategoriName): int
    {
        $mkWords  = $this->normalize($mkName);
        $katWords = $this->normalize($kategoriName);

        $score = 0;

        // 1. Exact match
        $score += count(array_intersect($mkWords, $katWords)) * 10;

        // 2. Synonym match
        foreach ($mkWords as $w1) {
            foreach ($katWords as $w2) {
                if (isset($this->synonyms()[$w1]) && in_array($w2, $this->synonyms()[$w1])) {
                    $score += 20;
                }
            }
        }

        // 3. Similar words (statistik vs statistika)
        foreach ($mkWords as $w1) {
            foreach ($katWords as $w2) {
                $distance = levenshtein($w1, $w2);
                if ($distance <= 2) {
                    $score += 15;
                }
            }
        }

        // Convert ke bobot
        return $score >= 50 ? 5 : ($score >= 20 ? 3 : 1);
    }

    private function normalize(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9 ]/', ' ', $text);
        $words = explode(' ', $text);

        $stop = ['dan','pengantar','proyek','mata','kuliah','dasar','lanjut'];

        return array_values(array_diff($words, $stop));
    }

    private function synonyms(): array
    {
        return [
            "web" => ["website", "frontend", "backend", "html", "css", "javascript"],
            "programming" => ["pemrograman", "coding", "algoritma"],
            "database" => ["basis", "data", "mysql", "sql"],
            "networking" => ["jaringan", "router", "switch"],
            "statistika" => ["statistik", "probabilitas"],
        ];
    }
}
