<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $subjects = [
//            'Математика', 'Физика', 'Химия', 'Биология', 'География',
//            'История', 'Литература', 'Русский язык', 'Английский язык',
//            'Информатика', 'Обществознание', 'Физическая культура', 'Технология'
//        ];
//
//        foreach ($subjects as $subject) {
//            Subject::firstOrCreate(['subject' => $subject]);
//        }

        // Получаем данные из первой таблицы
        $sourceData = DB::table('modx_testing_subjects')->get();

        foreach ($sourceData as $row) {
            DB::table('subjects')->insert([
                'subject' => $row->name, // Переносим name в subject
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
