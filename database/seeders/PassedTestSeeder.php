<?php

namespace Database\Seeders;

use App\Models\PassedTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PassedTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PassedTest::factory(100)->create();
    }
}
