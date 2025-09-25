<?php

namespace App\Services;

use App\Models\PassedTest;
use App\Models\Test;

class TestService
{
    static function showAllTests($model)
    {
        if (!$model->is_payment) {
            return [collect(), collect()];
         }
        // Получаем пройденные тесты
        $passedTests = PassedTest::with(['subject', 'type'])
            ->where('child_id', $model->id)
            ->where('grade_id', $model->grade_id)
            ->get();

        // получаем test_id пройденных тестов
        $passedTestIds = collect($passedTests)->pluck('test_id')->toArray();

        // получаем список оставшихся тестов
        $remainingTests = Test::where('grade_id', $model->grade_id)
            ->whereNotIn('tests.id', $passedTestIds)
            ->with(['subject', 'type']) // Подгружаем subject и type
            ->get();


        return [
            $remainingTests,
            $passedTests
        ];

    }
}
