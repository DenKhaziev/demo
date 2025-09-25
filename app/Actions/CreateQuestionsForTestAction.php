<?php

namespace App\Actions;

use App\Models\Test;
use Illuminate\Http\Request;

class CreateQuestionsForTestAction
{
    public function handle(array $questionsData, Test $test, Request $request): void
    {
        foreach ($questionsData as $index => $questionData) {
            // Обработка изображения
            $imagePath = null;
            if ($request->hasFile("questions.$index.image")) {
                $image = $request->file("questions.$index.image");
                $path = $image->store('images', 'public');
                $imagePath = basename($path);
            }

            $question = $test->questions()->create([
                'question' => $questionData['question'],
                'has_image' => $questionData['has_image'] ?? false,
                'image_path' => $imagePath,
            ]);

            foreach ($questionData['answers'] as $answerData) {
                $question->answers()->create([
                    'answer' => $answerData['answer'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]);
            }
        }
    }
}

