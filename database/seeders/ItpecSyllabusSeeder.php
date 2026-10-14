<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Choice;
use App\Models\User;
use App\Models\SourcePackage;

class ItpecSyllabusSeeder extends Seeder
{
    /**
     * Seed comprehensive syllabus topics and questions aligned with the official ITPEC Common Examination Outline.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. Official ITPEC IP Topics by Field
        $syllabusTopics = [
            // ==========================================
            // FIELD 1: STRATEGY (~35% Weight in ITPEC IP)
            // ==========================================
            [
                'code' => 'STRAT-CORP',
                'field' => 'strategy',
                'name' => 'Corporate Strategy & Business Activities',
                'description' => 'Business management, organizational structure, business models, SWOT analysis, and marketing strategies.',
                'color' => '#854D0E',
                'sort_order' => 1,
            ],
            [
                'code' => 'STRAT-IS',
                'field' => 'strategy',
                'name' => 'Information Systems Strategy',
                'description' => 'Enterprise architecture, business process re-engineering (BPR), DX initiatives, and IT investment evaluation.',
                'color' => '#A16207',
                'sort_order' => 2,
            ],
            [
                'code' => 'STRAT-FIN',
                'field' => 'strategy',
                'name' => 'Accounting & Financial Affairs',
                'description' => 'Balance sheets, income statements, cash flow, ROI, break-even point analysis, and IT cost management.',
                'color' => '#CA8A04',
                'sort_order' => 3,
            ],
            [
                'code' => 'STRAT-LEGAL',
                'field' => 'strategy',
                'name' => 'Legal Affairs & Compliance',
                'description' => 'Intellectual property, copyright, patent laws, GDPR/privacy laws, software licensing, and labor standards.',
                'color' => '#D97706',
                'sort_order' => 4,
            ],

            // ==========================================
            // FIELD 2: MANAGEMENT (~25% Weight in ITPEC IP)
            // ==========================================
            [
                'code' => 'MGMT-DEV',
                'field' => 'management',
                'name' => 'Development Techniques & Software Lifecycle',
                'description' => 'Waterfall, Agile, Scrum, requirements engineering, design, testing methodologies (unit/integration/system), and QA.',
                'color' => '#047857',
                'sort_order' => 5,
            ],
            [
                'code' => 'MGMT-PROJ',
                'field' => 'management',
                'name' => 'Project Management',
                'description' => 'WBS, Gantt charts, critical path method (CPM), schedule, cost, quality control, and risk management.',
                'color' => '#059669',
                'sort_order' => 6,
            ],
            [
                'code' => 'MGMT-SVC',
                'field' => 'management',
                'name' => 'Service Management & System Operations',
                'description' => 'ITIL concepts, SLA (Service Level Agreement), incident & problem management, change control, and system facilities.',
                'color' => '#10B981',
                'sort_order' => 7,
            ],
            [
                'code' => 'MGMT-AUDIT',
                'field' => 'management',
                'name' => 'System Audit & Internal Controls',
                'description' => 'Information system audit principles, internal control frameworks, auditor independence, and compliance reporting.',
                'color' => '#34D399',
                'sort_order' => 8,
            ],

            // ==========================================
            // FIELD 3: TECHNOLOGY (~40% Weight in ITPEC IP)
            // ==========================================
            [
                'code' => 'TECH-SEC',
                'field' => 'technology',
                'name' => 'Information Security & Cyber Defense',
                'description' => 'CIA triad, malware, phishing, cryptographic systems (AES, RSA), digital signatures, PKI, and access control.',
                'color' => '#1E3A8A',
                'sort_order' => 9,
            ],
            [
                'code' => 'TECH-NET',
                'field' => 'technology',
                'name' => 'Networks & Internet Communications',
                'description' => 'TCP/IP, OSI model, IP addresses (IPv4/IPv6), subnetting, DNS, HTTP/HTTPS, wireless LAN (Wi-Fi), and routers.',
                'color' => '#1D4ED8',
                'sort_order' => 10,
            ],
            [
                'code' => 'TECH-DB',
                'field' => 'technology',
                'name' => 'Databases & Data Management',
                'description' => 'Relational database model, SQL queries, primary/foreign keys, normalization (1NF-3NF), and ACID transaction properties.',
                'color' => '#2563EB',
                'sort_order' => 11,
            ],
            [
                'code' => 'TECH-HW',
                'field' => 'technology',
                'name' => 'Computer Systems, Hardware & OS',
                'description' => 'Von Neumann architecture, CPU, cache memory, RAM vs ROM, virtual memory, RAID, and operating system functions.',
                'color' => '#3B82F6',
                'sort_order' => 12,
            ],
            [
                'code' => 'TECH-PSEUDO',
                'field' => 'technology',
                'name' => 'Programming Logic & Pseudocode (April 2024+)',
                'description' => 'ITPEC pseudoprogramming language, conditional branches, loops, tracing variable states, and algorithm logic.',
                'color' => '#6366F1',
                'sort_order' => 13,
            ],
        ];

        $topicMap = [];
        foreach ($syllabusTopics as $data) {
            $topic = Topic::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'field' => $data['field'],
                    'description' => $data['description'],
                    'color' => $data['color'],
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                ]
            );
            $topicMap[$data['code']] = $topic;
        }

        // Remap legacy topics if existing
        Topic::where('code', 'ITF')->update(['field' => 'technology', 'is_active' => false]);
        Topic::where('code', 'BM')->update(['field' => 'management', 'is_active' => false]);
        Topic::where('code', 'TECH')->update(['field' => 'technology', 'is_active' => false]);
        Topic::where('code', 'LC')->update(['field' => 'strategy', 'is_active' => false]);

        // 2. Comprehensive Seed Questions Aligned with Official Outline
        $questions = [
            // --- STRATEGY QUESTIONS ---
            [
                'topic_code' => 'STRAT-CORP',
                'text' => 'In business strategy analysis, what is the primary purpose of conducting a SWOT analysis?',
                'difficulty' => 'easy',
                'correct' => 'C',
                'explanation' => 'A SWOT analysis assesses internal Strengths and Weaknesses along with external Opportunities and Threats to guide strategic planning.',
                'choices' => [
                    'A' => 'To calculate corporate net profit after tax deduction',
                    'B' => 'To track daily server CPU utilization and disk storage capacity',
                    'C' => 'To analyze internal Strengths and Weaknesses, as well as external Opportunities and Threats',
                    'D' => 'To audit compliance with software intellectual property agreements',
                ],
            ],
            [
                'topic_code' => 'STRAT-IS',
                'text' => 'Which concept describes radically redesigning business workflows and core processes to achieve dramatic improvements in cost, quality, service, and speed using IT?',
                'difficulty' => 'medium',
                'correct' => 'B',
                'explanation' => 'BPR (Business Process Re-engineering) involves rethinking and radically redesigning business processes to achieve dramatic performance breakthroughs.',
                'choices' => [
                    'A' => 'ERP (Enterprise Resource Planning)',
                    'B' => 'BPR (Business Process Re-engineering)',
                    'C' => 'CRM (Customer Relationship Management)',
                    'D' => 'SLA (Service Level Agreement)',
                ],
            ],
            [
                'topic_code' => 'STRAT-FIN',
                'text' => 'In financial accounting, what is the term for the sales volume at which total revenues exactly equal total costs (resulting in zero profit and zero loss)?',
                'difficulty' => 'medium',
                'correct' => 'A',
                'explanation' => 'The Break-Even Point (BEP) is the point where total revenue equals total costs (fixed costs + variable costs).',
                'choices' => [
                    'A' => 'Break-even Point',
                    'B' => 'Return on Investment (ROI)',
                    'C' => 'Gross Margin Point',
                    'D' => 'Liquidity Ratio',
                ],
            ],
            [
                'topic_code' => 'STRAT-LEGAL',
                'text' => 'Under software copyright and intellectual property principles, which of the following is protected by copyright in a computer software product?',
                'difficulty' => 'medium',
                'correct' => 'D',
                'explanation' => 'Copyright protects the specific expression of an idea (source code, object code, documentation), but does NOT protect the underlying algorithms, mathematical principles, or programming languages.',
                'choices' => [
                    'A' => 'The underlying mathematical algorithm used by the code',
                    'B' => 'The programming language grammar and keywords',
                    'C' => 'The abstract business idea behind the application',
                    'D' => 'The source code and object code as creative expressions',
                ],
            ],

            // --- MANAGEMENT QUESTIONS ---
            [
                'topic_code' => 'MGMT-DEV',
                'text' => 'In software testing methodologies, which test level validates that individual software modules or functions operate correctly in isolation?',
                'difficulty' => 'easy',
                'correct' => 'B',
                'explanation' => 'Unit testing verifies the behavior of the smallest testable parts of an application (functions, methods, classes) independently.',
                'choices' => [
                    'A' => 'Integration Testing',
                    'B' => 'Unit Testing',
                    'C' => 'System Testing',
                    'D' => 'Acceptance Testing',
                ],
            ],
            [
                'topic_code' => 'MGMT-PROJ',
                'text' => 'In project management, what is the Critical Path in a project network diagram (PERT chart)?',
                'difficulty' => 'hard',
                'correct' => 'C',
                'explanation' => 'The critical path is the longest sequence of dependent activities; any delay on the critical path directly delays the overall project completion date.',
                'choices' => [
                    'A' => 'The path containing the lowest-cost tasks',
                    'B' => 'The shortest path through the project network',
                    'C' => 'The sequence of dependent tasks that determines the minimum total project duration',
                    'D' => 'The tasks assigned to senior project management executives',
                ],
            ],
            [
                'topic_code' => 'MGMT-SVC',
                'text' => 'What is the primary document agreed upon between an IT service provider and customer that formally specifies the expected service targets, uptime, and performance metrics?',
                'difficulty' => 'easy',
                'correct' => 'A',
                'explanation' => 'An SLA (Service Level Agreement) formally defines service scope, availability targets (e.g., 99.9% uptime), and penalties for non-compliance.',
                'choices' => [
                    'A' => 'SLA (Service Level Agreement)',
                    'B' => 'NDA (Non-Disclosure Agreement)',
                    'C' => 'RFP (Request for Proposal)',
                    'D' => 'MOU (Memorandum of Understanding)',
                ],
            ],
            [
                'topic_code' => 'MGMT-AUDIT',
                'text' => 'Which attribute is essential for an internal or external IT auditor to maintain objective and credible findings regarding system controls?',
                'difficulty' => 'medium',
                'correct' => 'B',
                'explanation' => 'Auditor independence (freedom from operational bias or direct involvement in audited systems) is fundamental to professional auditing standards.',
                'choices' => [
                    'A' => 'Daily involvement in system programming',
                    'B' => 'Organizational and professional independence from audited activities',
                    'C' => 'Direct authority to terminate non-compliant employees',
                    'D' => 'Ownership of company shares and vendor contracts',
                ],
            ],

            // --- TECHNOLOGY QUESTIONS ---
            [
                'topic_code' => 'TECH-SEC',
                'text' => 'In public key cryptography (asymmetric encryption), if Alice wants to send a confidential message to Bob so that ONLY Bob can decrypt it, which key must Alice use to encrypt the message?',
                'difficulty' => 'medium',
                'correct' => 'B',
                'explanation' => 'For confidentiality in asymmetric cryptography, the sender encrypts with the recipient\'s Public Key. Only the recipient possesses the corresponding Private Key to decrypt it.',
                'choices' => [
                    'A' => 'Alice\'s Private Key',
                    'B' => 'Bob\'s Public Key',
                    'C' => 'Alice\'s Public Key',
                    'D' => 'Bob\'s Private Key',
                ],
            ],
            [
                'topic_code' => 'TECH-NET',
                'text' => 'What is the primary function of the Domain Name System (DNS) on the internet?',
                'difficulty' => 'easy',
                'correct' => 'C',
                'explanation' => 'DNS translates human-readable domain names (such as itpec.org) into computer-routable IP addresses (such as 192.0.2.1).',
                'choices' => [
                    'A' => 'To assign dynamic IP addresses to connecting local workstations',
                    'B' => 'To filter malicious network traffic using packet inspection',
                    'C' => 'To translate human-friendly domain names into numerical IP addresses',
                    'D' => 'To encrypt web application communications using SSL/TLS',
                ],
            ],
            [
                'topic_code' => 'TECH-DB',
                'text' => 'Which property of a relational database transaction guarantees that either ALL modifications in the transaction succeed, or NONE of them are applied (all-or-nothing)?',
                'difficulty' => 'medium',
                'correct' => 'A',
                'explanation' => 'Atomicity (the \'A\' in ACID) ensures that a database transaction is treated as a single indivisible unit: either all changes commit, or the transaction rolls back completely.',
                'choices' => [
                    'A' => 'Atomicity',
                    'B' => 'Consistency',
                    'C' => 'Isolation',
                    'D' => 'Durability',
                ],
            ],
            [
                'topic_code' => 'TECH-HW',
                'text' => 'What is the main operational difference between RAM (Random Access Memory) and SSD (Solid State Drive) storage in a computer?',
                'difficulty' => 'easy',
                'correct' => 'D',
                'explanation' => 'RAM is volatile high-speed primary memory that loses its contents when power is turned off. SSD is non-volatile flash storage that retains data persistently.',
                'choices' => [
                    'A' => 'RAM is non-volatile persistent storage; SSD loses all data on shutdown',
                    'B' => 'RAM connects directly to the monitor; SSD connects to the keyboard',
                    'C' => 'SSD operates significantly faster than CPU L1 cache memory',
                    'D' => 'RAM is volatile memory cleared on power loss; SSD is non-volatile persistent storage',
                ],
            ],

            // --- APRIL 2024 PSEUDOPROGRAMMING QUESTIONS ---
            [
                'topic_code' => 'TECH-PSEUDO',
                'text' => "Consider the following pseudocode segment written in the ITPEC standard format:\n\n```text\ntotal = 0\nFOR number = 1 TO 4\n    IF number % 2 == 0 THEN\n        total = total + number\n    END IF\nEND FOR\nDISPLAY total\n```\n\nWhat value will the program display?",
                'difficulty' => 'medium',
                'correct' => 'B',
                'explanation' => "Step-by-step trace:\n- number = 1: 1 % 2 != 0 (skip)\n- number = 2: 2 % 2 == 0 -> total = 0 + 2 = 2\n- number = 3: 3 % 2 != 0 (skip)\n- number = 4: 4 % 2 == 0 -> total = 2 + 4 = 6\nAfter loop completes, DISPLAY total outputs 6.",
                'choices' => [
                    'A' => '4',
                    'B' => '6',
                    'C' => '10',
                    'D' => '2',
                ],
            ],
            [
                'topic_code' => 'TECH-PSEUDO',
                'text' => "Trace the execution of this ITPEC pseudocode:\n\n```text\nx = 10\ny = 3\nWHILE x > y\n    x = x - 3\n    y = y + 1\nEND WHILE\nDISPLAY x\n```\n\nWhat number is displayed when the program finishes?",
                'difficulty' => 'hard',
                'correct' => 'C',
                'explanation' => "Trace:\n- Start: x = 10, y = 3\n- Iteration 1: 10 > 3 is true -> x = 10 - 3 = 7, y = 3 + 1 = 4\n- Iteration 2: 7 > 4 is true -> x = 7 - 3 = 4, y = 4 + 1 = 5\n- Condition check: 4 > 5 is false. Loop terminates.\n- DISPLAY x outputs 4.",
                'choices' => [
                    'A' => '1',
                    'B' => '3',
                    'C' => '4',
                    'D' => '7',
                ],
            ],
        ];

        foreach ($questions as $index => $q) {
            $topic = $topicMap[$q['topic_code']] ?? null;
            if (!$topic) continue;

            $question = Question::updateOrCreate(
                [
                    'topic_id' => $topic->id,
                    'question_text' => $q['text'],
                ],
                [
                    'source_question_number' => $index + 1,
                    'correct_answer_code' => $q['correct'],
                    'explanation' => $q['explanation'],
                    'explanation_type' => 'admin_created',
                    'explanation_approved' => true,
                    'difficulty' => $q['difficulty'],
                    'status' => 'published',
                    'created_by' => $admin?->id,
                    'published_by' => $admin?->id,
                    'published_at' => now(),
                ]
            );

            foreach ($q['choices'] as $code => $text) {
                Choice::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'code' => $code,
                    ],
                    [
                        'text' => $text,
                        'display_order' => match($code) {
                            'A' => 1,
                            'B' => 2,
                            'C' => 3,
                            'D' => 4,
                            default => 1,
                        },
                    ]
                );
            }
        }
    }
}
