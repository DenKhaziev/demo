<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixAdditionalPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-additional-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $additionalQuestions = \App\Models\AdditionalQuestion::all();

        foreach ($additionalQuestions as $question) {
            $originalPath = $question->image_path;

            // Удаляем ведущий слэш (и другие лишние символы на всякий случай)
            $cleanPath = ltrim(trim($originalPath), '/');

            // Проверка: если путь изменился, только тогда сохраняем
            if ($cleanPath !== $originalPath) {
                $question->image_path = $cleanPath;
                $question->save();
                $this->info("Обновлён: {$originalPath} => {$cleanPath}");
            }
        }

        $this->info('Ведущий слэш успешно удалён из additional_questions!');
    }

}
