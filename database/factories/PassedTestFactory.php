<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PassedTest>
 */
class PassedTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private function generateQuestions($count)
    {
        $questions = [];
        for ($i = 0; $i < $count; $i++) {
            $correctIndex = rand(0, 3); // Случайный индекс правильного ответа

            $answers = [];
            for ($j = 0; $j < 4; $j++) {
                $answers[] = [
                    'answer_text' => $this->faker->sentence(3),
                    'is_correct' => $j === $correctIndex // Только один правильный
                ];
            }

            $questions[] = [
                'question_text' => $this->faker->sentence(8),
                'answers' => $answers
            ];
        }
        return $questions;
    }
    public function definition(): array
    {


        return [
            'child_id' => Child::query()->inRandomOrder()->first()->id,
            'test_id' => Test::query()->inRandomOrder()->first()->id,
            'subject_id' => Subject::query()->inRandomOrder()->first()->id,
            'grade_id' => Grade::query()->inRandomOrder()->first()->id,
            'test_type_id' => TestType::query()->inRandomOrder()->first()->id,
            'answers' => json_encode($this->generateQuestions(10)),
            'total_questions' => $this->faker->numberBetween(1, 10),
            'attempts_left' => $this->faker->numberBetween(0, 5),
            'score' => $this->faker->numberBetween(2, 5),
            'status' => $this->faker->numberBetween(0, 1),

        ];
    }
}
