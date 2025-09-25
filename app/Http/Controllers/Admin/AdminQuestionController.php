<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateQuestionsForTestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Models\Question;
use App\Models\Test;
use Inertia\Inertia;

class AdminQuestionController extends Controller
{
    public function store(StoreQuestionRequest $request, Test $test)
    {
        $data = $request->validated();
        app(CreateQuestionsForTestAction::class)->handle($data['questions'], $test, $request);
        return redirect()->route('tests.show', $test->id)->with('success', 'Вопрос успешно добавлен');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return Redirect()->route('tests.show', $question->test_id)->with('success', 'Вопрос успешно удален');
    }

    public function trashed(Test $test)
    {
      $questions = Question::with(['test.grade', 'test.subject', 'answers'])
            ->onlyTrashed()
            ->where('test_id', $test->id)
            ->get();
        return Inertia::render('Admin/AdminTrashedQuestionsByTest', [
            'questions' => $questions,
            'test' => $test->load('grade', 'subject', 'type'),
        ]);
    }

    public function restore(Question $question)
    {
        $question->update(['deleted_at' => null]);
        return redirect()->route('tests.show', $question->test_id)->with('success', 'Вопрос восстановлен');

    }
}
