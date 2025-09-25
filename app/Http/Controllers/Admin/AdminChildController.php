<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Requests\PersonalInfo\UpdateChildNameRequest;



class AdminChildController extends Controller
{

    public function index(Request $request)
    {
        $query = Child::with('grade');

        // Фильтрация по статусу
        if ($request->status === 'new') {
            $query->where('is_payment', false);
        } elseif ($request->status === 'active') {
            $query->where('is_payment', true);
        }

        // Фильтрация по поиску
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('grade')) {
            $query->where('grade_id', $request->grade);
        }

        // Пагинация
        $children = $query->orderByDesc('id')->paginate(50)->withQueryString();
        return Inertia::render('Admin/AdminChildrenList', [
            'children' => $children,
            'status' => $request->status ?? 'active',
            'search' => $request->search ?? '',
            'grade' => $request->grade ?? null,
        ]);
    }

    public function pay(Child $child)
    {
        $child->update(['is_payment' => true, 'payment_date' => now()]);
        return redirect()->back()->with('success', 'Статус ученика изменен на "оплачено"');
    }

    public function update(UpdateChildNameRequest $request ,Child $child)
    {
        $data = $request->validated();
        $child->update($data);

        return redirect()->back()->with('success', 'ФИО ученика успешно изменено');
    }

    public function gradeUp(Child $child)
    {
        if ($child->grade_id < 11) {
            $child->update(['grade_id' => $child->grade_id + 1, 'is_payment' => false]);

        }
        return Redirect::back()->with('success', 'Ученик переведен в следующий класс');
    }

    public function show(Child $child)
    {

        [$remainingTests, $passedTests] = TestService::showAllTests($child);

        // Добавим answerObjects для каждого passedTest
        foreach ($passedTests as $test) {
            $answerIds = collect($test['answers'])->flatten()->unique();

            $test['answerObjects'] = \App\Models\Answer::with('question.answers')
                ->whereIn('id', $answerIds)
                ->get()
                ->values(); // важен values(), чтобы был массив, а не коллекция
        }
        return Inertia::render('Admin/AdminChildShow', [
            'remainingTests' => $remainingTests,
            'passedTests' => $passedTests,
            'child' => $child->load('user', 'grade', 'documents', 'userInfo')
        ]);
    }

    public  function destroy(Child $child)
    {
        $child->delete();
        return Redirect::back()->with('success', 'ученик удален');
    }
    public function count()
    {
        $count = Child::where('is_payment', true)->count();
        return response()->json(['count' => $count]);

    }


}
