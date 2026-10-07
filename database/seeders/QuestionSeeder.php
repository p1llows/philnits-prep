<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Topic;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        
        $itfTopic = Topic::where('code', 'ITF')->first() ?? Topic::first();
        $bmTopic = Topic::where('code', 'BM')->first() ?? Topic::first();
        $techTopic = Topic::where('code', 'TECH')->first() ?? Topic::first();
        $lcTopic = Topic::where('code', 'LC')->first() ?? Topic::first();

        $sampleQuestions = [
            // Question 1 (ITF)
            [
                'topic_id' => $itfTopic->id,
                'source_question_number' => 1,
                'question_text' => 'Which of the following network top-level protocols is primarily used to securely transfer files over an encrypted SSH connection?',
                'difficulty' => 'easy',
                'correct_answer_code' => 'B',
                'explanation' => 'SFTP (SSH File Transfer Protocol) runs over an SSH session to provide secure file transfer and management capabilities.',
                'choices' => [
                    'A' => 'FTP (File Transfer Protocol)',
                    'B' => 'SFTP (SSH File Transfer Protocol)',
                    'C' => 'TFTP (Trivial File Transfer Protocol)',
                    'D' => 'HTTP (Hypertext Transfer Protocol)',
                ]
            ],
            // Question 2 (ITF)
            [
                'topic_id' => $itfTopic->id,
                'source_question_number' => 2,
                'question_text' => 'In computer architecture, what is the primary role of the CPU Cache memory?',
                'difficulty' => 'medium',
                'correct_answer_code' => 'C',
                'explanation' => 'Cache memory is high-speed SRAM located close to the CPU core to store frequently accessed data and reduce memory latency.',
                'choices' => [
                    'A' => 'To permanently store system boot files and firmware',
                    'B' => 'To extend the physical storage capacity of the primary hard disk drive',
                    'C' => 'To temporarily hold frequently accessed data to reduce latency from RAM accesses',
                    'D' => 'To perform arithmetic and logic calculations on floating-point numbers',
                ]
            ],
            // Question 3 (TECH)
            [
                'topic_id' => $techTopic->id,
                'source_question_number' => 3,
                'question_text' => 'Which database normalization level requires that all non-key attributes depend entirely on the primary key (no partial functional dependency)?',
                'difficulty' => 'medium',
                'correct_answer_code' => 'B',
                'explanation' => 'Second Normal Form (2NF) requires that a relation is in 1NF and every non-prime attribute is fully functionally dependent on any key candidate.',
                'choices' => [
                    'A' => 'First Normal Form (1NF)',
                    'B' => 'Second Normal Form (2NF)',
                    'C' => 'Third Normal Form (3NF)',
                    'D' => 'Boyce-Codd Normal Form (BCNF)',
                ]
            ],
            // Question 4 (TECH)
            [
                'topic_id' => $techTopic->id,
                'source_question_number' => 4,
                'question_text' => 'In object-oriented programming, which principle allows a single interface to represent different underlying data types or classes?',
                'difficulty' => 'easy',
                'correct_answer_code' => 'A',
                'explanation' => 'Polymorphism allows objects of different classes to respond to the same method call in their own specific ways.',
                'choices' => [
                    'A' => 'Polymorphism',
                    'B' => 'Encapsulation',
                    'C' => 'Inheritance',
                    'D' => 'Abstraction',
                ]
            ],
            // Question 5 (BM)
            [
                'topic_id' => $bmTopic->id,
                'source_question_number' => 5,
                'question_text' => 'What is the main objective of a Business Impact Analysis (BIA) in Disaster Recovery planning?',
                'difficulty' => 'hard',
                'correct_answer_code' => 'C',
                'explanation' => 'A Business Impact Analysis quantifies the financial, operational, and reputational impacts of disruptions on key processes.',
                'choices' => [
                    'A' => 'To audit employees for adherence to cybersecurity policies',
                    'B' => 'To select hardware vendors for offsite backup servers',
                    'C' => 'To identify critical business functions and quantify the consequences of operational disruptions',
                    'D' => 'To schedule routine software updates across company workstations',
                ]
            ],
            // Question 6 (BM)
            [
                'topic_id' => $bmTopic->id,
                'source_question_number' => 6,
                'question_text' => 'Which Agile project management practice involves a daily 15-minute standing meeting for syncs and impediment identification?',
                'difficulty' => 'easy',
                'correct_answer_code' => 'D',
                'explanation' => 'Daily Standup (or Daily Scrum) is a short daily alignment meeting for the development team.',
                'choices' => [
                    'A' => 'Sprint Planning',
                    'B' => 'Sprint Retrospective',
                    'C' => 'Backlog Grooming',
                    'D' => 'Daily Standup / Daily Scrum',
                ]
            ],
            // Question 7 (LC)
            [
                'topic_id' => $lcTopic->id,
                'source_question_number' => 7,
                'question_text' => 'Under General Data Protection regulations, which right permits individuals to request the complete deletion of their personal data?',
                'difficulty' => 'medium',
                'correct_answer_code' => 'A',
                'explanation' => 'The Right to be Forgotten (Right to Erasure) allows data subjects to request data controllers to delete personal data under specific grounds.',
                'choices' => [
                    'A' => 'Right to Erasure (Right to be Forgotten)',
                    'B' => 'Right to Data Portability',
                    'C' => 'Right of Access',
                    'D' => 'Right to Object',
                ]
            ],
            // Question 8 (LC)
            [
                'topic_id' => $lcTopic->id,
                'source_question_number' => 8,
                'question_text' => 'What type of legal protection grants an inventor exclusive rights to prevent others from making, using, or selling a technical invention?',
                'difficulty' => 'easy',
                'correct_answer_code' => 'B',
                'explanation' => 'A Patent grants exclusive legal rights to a technical invention for a set period.',
                'choices' => [
                    'A' => 'Copyright',
                    'B' => 'Patent',
                    'C' => 'Trademark',
                    'D' => 'Trade Secret',
                ]
            ],
        ];

        foreach ($sampleQuestions as $qData) {
            $question = Question::create([
                'topic_id' => $qData['topic_id'],
                'source_question_number' => $qData['source_question_number'],
                'question_text' => $qData['question_text'],
                'correct_answer_code' => $qData['correct_answer_code'],
                'explanation' => $qData['explanation'],
                'explanation_type' => 'admin_created',
                'explanation_approved' => true,
                'difficulty' => $qData['difficulty'],
                'status' => 'published',
                'created_by' => $admin?->id,
                'published_by' => $admin?->id,
                'published_at' => now(),
            ]);

            foreach (['A', 'B', 'C', 'D'] as $index => $code) {
                Choice::create([
                    'question_id' => $question->id,
                    'code' => $code,
                    'text' => $qData['choices'][$code],
                    'display_order' => $index + 1,
                ]);
            }
        }
    }
}
