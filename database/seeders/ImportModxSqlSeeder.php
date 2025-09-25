<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportModxSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dumpPathSubjects = base_path('database/dumps/modx_testing_subjects.sql');
        $dumpPathItems = base_path('database/dumps/modx_testing_items.sql');

        DB::unprepared(file_get_contents($dumpPathSubjects));
        DB::unprepared(file_get_contents($dumpPathItems));
    }
}
