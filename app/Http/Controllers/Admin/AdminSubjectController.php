<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminSubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('subject')->get();
        return Inertia::render('Admin/AdminSubjects', [
            'subjects' => $subjects
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/AdminCreateSubject', [
            'subjects' => Subject::all(),
        ]);
    }

    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();
        Subject::create($data);
        return redirect()->back()->with('success', 'Новый предмет успешно создан');
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $data = $request->validated();
        $subject->update($data);

        return redirect()->back()->with('success', 'Предмет успешно обновлен');
    }

    public  function destroy(Subject $subject)
    {
        $subject->delete();
        return Redirect::back()->with('success', 'предмет удален');
    }

    public function trashed()
    {
        $subjects = Subject::withTrashed()->whereNotNull('deleted_at')->get();
        return Inertia::render('Admin/AdminTrashedSubjects', [
            'subjects' => $subjects
        ]);
    }
     public function restore(Subject $subject)
     {
         $subject->update(['deleted_at' => null]);
         return Redirect::back()->with('success', 'Предмет восстановлен');
     }

}
