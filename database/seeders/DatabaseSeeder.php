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
        User::create([
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

        // Create sample topics based on PhilNITS IP Passport exam structure
        $topics = [
            [
                'name' => 'Information Technology Fundamentals',
                'code' => 'ITF',
                'description' => 'Basic IT concepts including computer systems, networks, and information security',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Business and Management',
                'code' => 'BM',
                'description' => 'IT governance, risk management, business continuity, and project management',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Technology',
                'code' => 'TECH',
                'description' => 'Technical implementation including databases, AI, blockchain, and emerging technologies',
                'color' => '#F59E0B',
                'sort_order' => 3,
            ],
            [
                'name' => 'Legal and Compliance',
                'code' => 'LC',
                'description' => 'Intellectual property, contracts, privacy laws, and regulatory compliance',
                'color' => '#EC4899',
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
        ]);
    }
}
