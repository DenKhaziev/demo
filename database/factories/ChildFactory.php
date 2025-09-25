<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    private static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'grade_id' => Grade::query()->inRandomOrder()->first()->id,
            'name' => fake()->name(),
            'is_payment' => fake()->boolean(),
            'is_has_a_certificate' => fake()->boolean(),
//            'phone' => SchoolClass::query()->inRandomOrder()->first()->phone, // далее будет использован при регистрации реального юзера
            'email' => User::query()->inRandomOrder()->first()->email,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
