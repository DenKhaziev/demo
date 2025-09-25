<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateTestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Test\StoreTestRequest;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminTestController extends Controller
{

    public function index()
    {
        $tests = Test::with(['subject', 'grade', 'type'])
            ->withCount('questions')
            ->whereHas('subject', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('grade_id')
            ->get();
//        dd($tests[0]->type->label);
        return Inertia::render('Admin/AdminTests', [
            'tests' => $tests,
        ]);
    }

    public function show (Test $test)
    {
        // в модели есть акцептор для загрузки изображений!
        $questions = Question::with(['test.grade', 'test.subject', 'answers'])
            ->where('test_id', $test->id)
            ->get();

        return Inertia::render('Admin/AdminTestShow', [
            'questions' => $questions,
            'test' => $test->load('grade', 'subject', 'type'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/AdminCreateTest', [
            'testTypes' => TestType::all(),
            'subjects' => Subject::all(),
            'grades' => Grade::all(),
        ]);
    }

    public function store(StoreTestRequest $request)
    {
        $data = $request->validated();
        (new CreateTestAction)->handle($data, $request);
        return redirect()->route('tests.create')->with('success', 'Тест успешно создан');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return Redirect()->route('tests')->with('success', 'тест удален');
    }

    public function trashed()
    {
        $tests = Test::with(['subject', 'grade', 'type'])
            ->withCount('questions')
            ->onlyTrashed()
            ->whereHas('subject', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('grade_id')
            ->get();

        return Inertia::render('Admin/AdminTrashedTests', [
            'tests' => $tests,
        ]);
    }

    public function restore(Test $test)
    {
        $test->update(['deleted_at' => null]);
        return Redirect::back()->with('success', 'Тест восстановлен');
    }
}
