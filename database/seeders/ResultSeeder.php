<?php

// database/seeders/ResultSeeder.php

namespace Database\Seeders;

use App\Models\PassedTest;
use Illuminate\Database\Seeder;
use App\Models\Test;
use Illuminate\Support\Facades\DB;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        $childId = 1;

        // Получаем тесты по условиям: grade_id = 1 и test_type_id = 3
        $tests = Test::where('grade_id', 1)
            ->where('test_type_id', 3)
            ->get();

        foreach ($tests as $test) {
            PassedTest::create([
                'child_id' => $childId,
                'test_id' => $test->id,
                'subject_id' => $test->subject_id,
                'grade_id' => $test->grade_id,
                'test_type_id' => $test->test_type_id,
                'answers' => json_encode([
                    ['question_id' => 1, 'answer' => 'A', 'is_correct' => rand(0, 1)],
                    ['question_id' => 2, 'answer' => 'B', 'is_correct' => rand(0, 1)],
                ]),
                'correct_answers_count' => rand(3, 10),
                'max_score' => 10,
                'score' => rand(2, 5),
                'status' => 1,
                'passed_at' => now(),
                'time_spent' => rand(300, 900), // от 5 до 15 минут
                'total_questions' => 10,
                'attempts_left' => rand(5, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

