<?php

namespace Database\Seeders;

use App\Models\TestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'demo', 'optional', 'required'
        ];

        foreach ($subjects as $subject) {
            TestType::firstOrCreate(['type' => $subject]);
        }
    }
}
