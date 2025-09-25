<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\StoreTestRequest;
use App\Models\Child;
use App\Models\Grade;
use App\Models\PassedTest;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Mockery\Generator\StringManipulation\Pass\Pass;
use function Laravel\Prompts\alert;

class AdminPassedTestController extends Controller
{
    public function attemptsUp(PassedTest $passedTest)
    {
        if ($passedTest->attempts_left == 0) {
            $passedTest->update(['attempts_left' => $passedTest->attempts_left + 2]);
        } else {
            alert('у ученика еще есть попытки');
        }
        return Redirect::back()->with('success', 'Попытки увеличены');
    }

    public function destroy(PassedTest $passedTest)
    {
        // softDelete
        $passedTest->delete();
        return Redirect::back()->with('success', 'тестирование обнулено');
    }
}
