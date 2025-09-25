<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GradeSubjectTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $test = Test::inRandomOrder()->first();
        $grade = Grade::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        return [
            'grade_id' => $grade->id,
            'subject_id' => $subject->id,
            'test_id' => $test->id,
        ];
    }
}
