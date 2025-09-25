<?php

namespace App\Console\Commands;

use App\Models\AdditionalQuestion;
use App\Models\Question;
use Illuminate\Console\Command;

class FixImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:image-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удалить префикс "/assets/questionsImages/" из путей изображений в таблице questions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Обработка таблицы questions
        $questions = \App\Models\Question::all();

        foreach ($questions as $question) {
            $originalPath = $question->image_path;

            // Удаляем все возможные варианты префиксов
            $cleanPath = preg_replace('#^/*assets/questionsImages/*#', '', trim($originalPath));

            // Убираем лишний ведущий /
            $cleanPath = ltrim($cleanPath, '/');

            if ($cleanPath !== $originalPath) {
                $question->image_path = $cleanPath;
                $question->save();
                $this->info("Questions: {$originalPath} => {$cleanPath}");
            }
        }

        // Обработка таблицы additional_questions
        $additionalQuestions = \App\Models\AdditionalQuestion::all();

        foreach ($additionalQuestions as $question) {
            $originalPath = $question->image_path;

            // Тот же подход
            $cleanPath = preg_replace('#^/*assets/questionsImages/*#', '', trim($originalPath));
            $cleanPath = ltrim($cleanPath, '/');

            if ($cleanPath !== $originalPath) {
                $question->image_path = $cleanPath;
                $question->save();
                $this->info("Additional: {$originalPath} => {$cleanPath}");
            }
        }

        $this->info('Пути успешно обновлены в обеих таблицах!');
    }


}
