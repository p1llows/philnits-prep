<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Topic;
use App\Models\SourcePackage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (for development only)
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@philnitsprep.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create learner user (for development only)
        User::create([
            'name' => 'Test Learner',
            'email' => 'learner@philnitsprep.local',
            'password' => bcrypt('password'),
            'role' => 'learner',
        ]);

        // Create topics based on official ITPEC IP 3-Field exam structure
        $topics = [
            [
                'name' => 'Information Technology Fundamentals',
                'field' => 'technology',
                'code' => 'ITF',
                'description' => 'Computer systems, hardware/software, networks, database systems, and information security',
                'color' => '#1F3A5F',
                'sort_order' => 1,
            ],
            [
                'name' => 'Business and Management',
                'field' => 'management',
                'code' => 'BM',
                'description' => 'Project management, software development processes, service management, and quality control',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Technology',
                'field' => 'technology',
                'code' => 'TECH',
                'description' => 'Algorithms, programming logic, emerging technologies, and systems architecture',
                'color' => '#3B82F6',
                'sort_order' => 3,
            ],
            [
                'name' => 'Legal and Compliance',
                'field' => 'strategy',
                'code' => 'LC',
                'description' => 'Corporate strategy, accounting, intellectual property, contracts, and compliance',
                'color' => '#F59E0B',
                'sort_order' => 4,
            ],
        ];

        foreach ($topics as $topic) {
            Topic::create($topic);
        }

        // Create a sample source package
        SourcePackage::create([
            'name' => 'PhilNITS IP Passport Exam - Sample Q&A 2024',
            'description' => 'Sample questions from the IP Passport examination for demonstration purposes',
            'source_name' => 'PhilNITS Official Examination Materials',
            'source_date' => '2024-Q1',
            'attribution_note' => 'Based on official PhilNITS IP Passport examination materials',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        // Seed questions
        $this->call(QuestionSeeder::class);
    }
}
