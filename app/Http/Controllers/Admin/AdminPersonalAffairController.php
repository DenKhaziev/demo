<?php

namespace App\Http\Controllers\Admin;

use App\Events\CertificateRequest;
use App\Events\PersonalAffairRequest;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\DocumentByGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdminPersonalAffairController extends Controller
{
    public function index()
    {
        // если первый класс и нет личного дела
        $personalAffairRequest = DocumentByGrade::with(['child.user'])
            ->where([['has_personal_affair', '=', 0], ['grade_id', '=', 1]])
            ->get();
        return Inertia::render('Admin/AdminPersonalAffairs', [
            'personalAffair' => $personalAffairRequest
        ]);
    }

    public function update(DocumentByGrade $documentByGrade)
    {
        $documentByGrade->update(['has_personal_affair' => true]);
        return Redirect::back()->with('success', 'Личное дело сформировано');
    }

    public function create()
    {
        return Inertia::render('PersonalAffairRequestSend');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'child_id' => 'required|numeric',
        ]);
        $personalAffair = DocumentByGrade::create([
            'grade_id' => 1,
            'child_id' => $data['child_id'],
            'has_personal_affair' => false,
        ]);
        $personalAffair->load('child.user');
        broadcast(new PersonalAffairRequest($personalAffair));

        return redirect()->back();
    }

    public function count()
    {
        $count = DocumentByGrade::where('has_personal_affair', false)->where('grade_id', 1)->count();
        return response()->json(['count' => $count]);
    }

}
