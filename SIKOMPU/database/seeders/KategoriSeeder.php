<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [

            // ====== SERTIFIKASI / SKILL AREA ======
            ['nama' => 'Web Development'],
            ['nama' => 'Mobile Development'],
            ['nama' => 'Frontend Engineering'],
            ['nama' => 'Backend Engineering'],
            ['nama' => 'Full Stack Development'],
            ['nama' => 'Database Administration'],
            ['nama' => 'Cloud Computing'],
            ['nama' => 'Cyber Security'],
            ['nama' => 'Data Science'],
            ['nama' => 'Machine Learning'],
            ['nama' => 'Artificial Intelligence'],
            ['nama' => 'UI/UX Design'],
            ['nama' => 'DevOps Engineering'],
            ['nama' => 'Software Quality Assurance'],
            ['nama' => 'Networking & Infrastructure'],
            ['nama' => 'IoT (Internet of Things)'],
            ['nama' => 'Game Development'],
            ['nama' => 'AR/VR Development'],
            ['nama' => 'Project Management'],
            ['nama' => 'IT Support & Helpdesk'],


            // ====== BIDANG PENELITIAN INFORMATIKA ======
            ['nama' => 'Computer Vision'],
            ['nama' => 'Natural Language Processing'],
            ['nama' => 'Robotics'],
            ['nama' => 'Big Data Analytics'],
            ['nama' => 'Software Engineering'],
            ['nama' => 'Information Systems'],
            ['nama' => 'Human Computer Interaction'],
            ['nama' => 'Distributed Systems'],
            ['nama' => 'Information Security'],
            ['nama' => 'High Performance Computing'],
            ['nama' => 'Smart City & Smart System'],
            ['nama' => 'Recommender System'],
            ['nama' => 'Educational Technology'],
            ['nama' => 'Digital Transformation'],
            ['nama' => 'Health Informatics']
        ];

        DB::table('kategori')->insert($kategoris);

        $this->command->info("Kategori berhasil ditambahkan!");
    }
}
