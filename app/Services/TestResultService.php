<?php

namespace App\Services;

use App\Models\PassedTest;
use App\Models\Test;
use Illuminate\Support\Facades\DB;

class TestResultService
{
    public function evaluateAndSave(int $testId, int $childId, array $clientAnswers, ?int $timeLeft = null): PassedTest
    {
        return DB::transaction(function () use ($testId, $childId, $clientAnswers, $timeLeft) {
            // Подгружаем вопросы + ответы (только нужные поля)
            $test = Test::with(['questions.answers' => function ($q) {
                $q->select('id', 'question_id', 'is_correct');
            }])->findOrFail($testId);

            // Оставляем только вопросы этого теста
            $questionIds = $test->questions->pluck('id')->all();

            $filtered = collect($clientAnswers)
                ->only($questionIds)
                ->map(function ($val) {
                    $arr = is_array($val) ? $val : [$val];
                    return collect($arr)
                        ->filter(fn($id) => is_numeric($id))
                        ->map(fn($id) => (int) $id)
                        ->unique()
                        ->values()
                        ->all();
                })
                ->toArray();

            // Проверяем принадлежность каждого answer соответствующему вопросу
            $answersByQuestion = $test->questions->mapWithKeys(
                fn($q) => [$q->id => $q->answers->pluck('id')->all()]
            );

            foreach ($filtered as $qid => $answerIds) {
                $allowed = $answersByQuestion[$qid] ?? [];
                $filtered[$qid] = array_values(array_intersect($answerIds, $allowed));
            }

            // Подсчёт правильных
            $correctCount = 0;
            foreach ($test->questions as $question) {
                $selected = $filtered[$question->id] ?? [];
                if (empty($selected)) {
                    continue; // без ответа — не увеличиваем счётчик
                }

                $selected = collect($selected)->sort()->values();
                $correctIds = $question->answers->where('is_correct', 1)->pluck('id')->sort()->values();

                if ($selected->count() === $correctIds->count() && $selected->diff($correctIds)->isEmpty()) {
                    $correctCount++;
                }
            }

            // Всего вопросов теста (ожидаем 10, но подстрахуемся)
            $totalQuestions = min(10, $test->questions->count());

            $percent = $totalQuestions > 0 ? (int) round($correctCount * 100 / $totalQuestions) : 0;

            $score = match (true) {
                $percent >= 85 => 5,
                $percent >= 70 => 4,
                $percent >= 50 => 3,
                default         => 2,
            };

            // Попытки — защитимся от гонок
            $lastAttempt = PassedTest::where('child_id', $childId)
                ->where('test_id', $testId)
                ->lockForUpdate()
                ->latest('passed_at')
                ->first();

            if ($lastAttempt && $lastAttempt->attempts_left <= 0) {
                abort(403, 'Вы исчерпали лимит попыток для этого теста.');
            }

            $attemptsLeft = $lastAttempt ? max(0, $lastAttempt->attempts_left - 1) : 9;

            // Нормализуем время
            $timeLeft = (int) ($timeLeft ?? 0);
            $timeLeft = max(0, min(3600, $timeLeft));
            $timeSpent = 3600 - $timeLeft;

            // Сохраняем/обновляем агрегат по тесту
            return PassedTest::updateOrCreate(
                [
                    'child_id'   => $childId,
                    'test_id'    => $testId,
                    'subject_id' => $test->subject_id,
                    'grade_id'   => $test->grade_id,
                ],
                [
                    'test_type_id'           => $test->test_type_id,
                    'answers'                => $filtered,
                    'correct_answers_count'  => $correctCount,
                    'max_score'              => $totalQuestions,
                    'score'                  => $score,
                    'status'                 => 1,
                    'passed_at'              => now(),
                    'time_spent'             => $timeSpent,
                    'total_questions'        => $totalQuestions,
                    'attempts_left'          => $attemptsLeft,
                ]
            );
        });
    }
}
