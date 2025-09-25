<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSubjectTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grade_subject_test')->truncate(); // Очищаем таблицу

        $tests = Test::all();
        $grades = Grade::all();
        $subjects = Subject::all();

        foreach ($tests as $test) {
            DB::table('grade_subject_test')->insert([
                'grade_id' => $grades->random()->id, // Случайный класс
                'subject_id' => $subjects->random()->id, // Случайный предмет
                'test_id' => $test->id, // Каждый тест записывается один раз
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
