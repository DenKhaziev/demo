<?php

namespace App\Exports;

use App\Models\Child;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChildrenReportExport implements FromView
{
    public function view(): View
    {
        $children = Child::with('userInfo')->get();
        return view('exports.children_report', [
            'children' => $children,
        ]);
    }
}

