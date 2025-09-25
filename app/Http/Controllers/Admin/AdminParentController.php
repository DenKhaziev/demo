<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\DocumentByGrade;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminParentController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query(); // только родители

        // Фильтр по статусу
        if ($request->status === 'new') {
            $query->whereHas('children', function ($query) {
                $query->where('is_payment', false);
            });
        }

        if ($request->status === 'active') {
            $query->whereHas('children', function ($query) {
                $query->where('is_payment', true);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $parents = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return inertia('Admin/AdminParentsList', [
            'parents' => $parents,
            'search' => $request->search,
            'status' => $request->status,
        ]);
    }

    public function children(User $parent)
    {
        $children = $parent->children()->with('documents')->get();
        return Inertia::render('Admin/AdminParentsChildren', [
            'parent' => $parent->load('tickets'),
            'children' => $children,
        ]);
    }

    public function gradeEdit(Request $request, Child $child)
    {
        $data = $request->validate([
            'grade' => ['required','integer','min:1','max:11'],
        ]);

        $child->update(['grade_id' => $data['grade']]);

        return back()->with('success', 'Класс обновлён');
    }



}
