<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_type_id' => TestType::query()->inRandomOrder()->first()->id,
            'subject_id' => Subject::query()->inRandomOrder()->first()->id,
            'grade_id' => Grade::query()->inRandomOrder()->first()->id,
            'allotted_time' => 60,
            'number_of_attempts' => 10,
        ];
    }
}
