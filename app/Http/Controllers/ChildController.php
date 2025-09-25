<?php

namespace App\Http\Controllers;


use App\Models\DocumentByGrade;
use App\Models\PassedTest;
use App\Models\Question;
use App\Models\Test;
use App\Services\TestService;
use Inertia\Inertia;
use App\Http\Requests\Test\SubmitTestRequest;
use App\Services\TestResultService;
use Illuminate\Http\RedirectResponse;


class ChildController extends Controller
{
    public function index()
    {
        $child = auth('child')->user();
        [$remainingTests, $passedTests] = TestService::showAllTests($child);

        return Inertia::render('Child/ChildMain', [
            'remainingTests' => $remainingTests,
            'passedTests' => $passedTests,
            'child' => $child->load('user', 'certificate', 'documents')
        ]);
    }

    public function testShow(Test $test)
    {
        $child = auth('child')->user();
        $existing = PassedTest::where('child_id', $child->id)
            ->where('test_id', $test->id)
            ->first();

        if ($existing && $existing->attempts_left <= 0) {
            abort(403, 'Вы исчерпали лимит попыток для этого теста.');
        }
        $questions = Question::with(['test.grade', 'test.subject', 'answers'])
            ->where('test_id', $test->id)
            ->inRandomOrder()
            ->limit(10)
            ->get();
        return Inertia::render('Child/ChildTest', [
            'questions' => $questions,
            'test' => $test->load('grade', 'subject', 'type'),
            'child' => $child
        ]);
    }



    public function submit(SubmitTestRequest $request, TestResultService $service): RedirectResponse
    {
        $service->evaluateAndSave(
            testId:   (int) $request->input('test_id'),
            childId:  (int) $request->input('child_id'),
            clientAnswers: $request->input('answers', []),
            timeLeft: $request->integer('time_left', 0),
        );

        return redirect()->route('student.index');
    }


}

