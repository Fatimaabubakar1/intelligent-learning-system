<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonsTableSeeder extends Seeder
{
    public function run(): void
    {

        Lesson::truncate();

        // Hausa Lessons
        Lesson::create([
            'title' => 'Hausa Greetings',
            'description' => 'Learn basic Hausa greetings and introductions',
            'content' => [
                [
                    'title' => 'Introduction',
                    'type' => 'text',
                    'data' => 'Hausa greetings are an important part of daily life. They show respect and create social bonds.'
                ],
                [
                    'title' => 'Common Greetings',
                    'type' => 'list',
                    'data' => [
                        'Sannu - Hello',
                        'Ina kwana? - Good morning (literally: How did you sleep?)',
                        'Barka da yini - Good day',
                        'Barka da dare - Good night',
                        'Na gode - Thank you'
                    ]
                ],
                [
                    'title' => 'Example Conversation',
                    'type' => 'example',
                    'data' => [
                        'sentence' => 'A: Sannu. Ina kwana?',
                        'translation' => 'A: Hello. Good morning?'
                ],
                [
                    'title' => 'Vocabulary',
                    'type' => 'vocabulary',
                    'data' => [
                        ['word' => 'sannu', 'meaning' => 'hello', 'pos' => 'NOUN'],
                        ['word' => 'lafiya', 'meaning' => 'fine/health', 'pos' => 'NOUN'],
                        ['word' => 'gode', 'meaning' => 'thank', 'pos' => 'VERB'],
                        ['word' => 'barka', 'meaning' => 'blessing', 'pos' => 'NOUN']
                    ]
                ]
            ],
            'language' => 'hausa',
            'level' => 'beginner',
            'duration' => 10,
            'order' => 1,
            'is_active' => true
        ]]);

        // Yoruba Intermediate Lesson
        Lesson::create([
            'title' => 'Yoruba Family Terms',
            'description' => 'Learn Yoruba vocabulary for family members and relationships',
            'content' => [
                [
                    'title' => 'Immediate Family',
                    'type' => 'list',
                    'items' => [
                        'Bàbá - Father',
                        'Ìyá - Mother',
                        'Ọmọ - Child',
                        'Ẹ̀gbọ́n - Elder sibling',
                        'Àbúrò - Younger sibling'
                    ]
                ],
                [
                    'title' => 'Extended Family',
                    'type' => 'list',
                    'items' => [
                        'Bàbá àgbà - Grandfather',
                        'Ìyá àgbà - Grandmother',
                        'Arákùnrin - Uncle',
                        'Arabìnrin - Aunt',
                        'Ọmọ ìyá - Cousin (mother\'s side)'
                    ]
                ]
            ],
            'language' => 'yoruba',
            'level' => 'intermediate',
            'duration' => 15,
            'order' => 2,
            'is_active' => true
        ]);

        // Igbo Lesson
        Lesson::create([
            'title' => 'Igbo Numbers 1-10',
            'description' => 'Learn to count from 1 to 10 in Igbo',
            'content' => [
                [
                    'title' => 'Basic Numbers',
                    'type' => 'list',
                    'items' => [
                        'Otu - One',
                        'Abụọ - Two',
                        'Atọ - Three',
                        'Anọ - Four',
                        'Ise - Five',
                        'Isii - Six',
                        'Asaa - Seven',
                        'Asatọ - Eight',
                        'Itoolu - Nine',
                        'Iri - Ten'
                    ]
                ],
                [
                    'title' => 'Practice Examples',
                    'type' => 'example',
                    'sentence' => 'Nwere m atọ ụmụ nwoke na abụọ ụmụ nwanyị.',
                    'translation' => 'I have three boys and two girls.'
                ]
            ],
            'language' => 'igbo',
            'level' => 'beginner',
            'duration' => 12,
            'order' => 1,
            'is_active' => true
        ]);

        Lesson::create([
            'title' => 'Hausa Numbers',
            'description' => 'Learn to count from 1 to 20 in Hausa',
            'content' => [
                [
                    'title' => 'Numbers 1-10',
                    'type' => 'list',
                    'items' => [
                        'Day - One',
                        'Biyu - Two',
                        'Uku - Three',
                        'Hudu - Four',
                        'Biyar - Five',
                        'Shida - Six',
                        'Bakwai - Seven',
                        'Takwas - Eight',
                        'Tara - Nine',
                        'Goma - Ten'
                    ]
                ]
            ],
            'language' => 'hausa',
            'level' => 'beginner',
            'duration' => 8,
            'order' => 2,
            'is_active' => true
        ]);

        echo "Seeded lessons successfully!\n";
    }
}
