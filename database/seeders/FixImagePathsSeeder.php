<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class FixImagePathsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();
        $qCount = 0;

        foreach ($questions as $question) {
            $originalPath = $question->image_path;

            if (!$originalPath) {
                continue;
            }

            $cleanPath = preg_replace('#^/*assets/questionsImages/*#', '', trim($originalPath));
            $cleanPath = ltrim($cleanPath, '/');

            if ($cleanPath !== $originalPath) {
                $question->image_path = $cleanPath;
                $question->save();
                $qCount++;
            }
        }

        $this->command->info("✔ Обновлены пути к изображениям: questions: {$qCount}");
    }
}
