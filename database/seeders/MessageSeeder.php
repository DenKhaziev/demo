<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('messages')->truncate();
        $faker = Faker::create();

        for ($i = 0; $i < 1000; $i++) {
            DB::table('messages')->insert([
                'ticket_id' => rand(1, 100),
                'user_id' => rand(1, 100),
                'body' => $faker->paragraph(),
                'is_admin' => rand(0, 1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
