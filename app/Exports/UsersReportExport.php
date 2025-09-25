<?php

namespace App\Exports;

use App\Models\Child;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersReportExport implements FromView
{
    public function view(): View
    {
        $children = Child::with('userInfo')->get();
        return view('exports.users_report', [
            'children' => $children,
        ]);
    }
}

