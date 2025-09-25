<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('certificates')->truncate(); // Очищаем таблицу

        for ($i = 0; $i < 40; $i++) {
            DB::table('certificates')->insert([
                'grade_id' => rand(1, 11), // Укажите правильный диапазон
                'child_id' => rand(1, 50), // Укажите правильный диапазон
                'is_has_a_certificate' => rand(0, 1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }


    }
}
