<?php

namespace App\Http\Controllers\Api\Child;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChildResource;
use App\Http\Resources\PassedTestResource;
use App\Http\Resources\TestResource;
use App\Models\Child;
use App\Models\PassedTest;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function show(Child $child) {
        // в адресной строке не либо будет id, либо фио ученика
        [$remainingTests, $passedTests] = TestService::showAllTests($child);

        return response()->json([
            'passedTests' => ChildResource::make($passedTests),
            'remainingTests' => ChildResource::make($remainingTests)
        ]);

    }
}
