<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestTypeResource;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index() {
//        $tests = Grade::with(['subjects' => function ($query) {
//            $query->with(['tests' => function ($testQuery) {
//                $testQuery->whereHas('grade', function ($gradeQuery) {
//                    $gradeQuery->whereColumn('grades.id', 'grade_subject_test.grade_id');
//                })
//                    ->whereHas('subject', function ($subjectQuery) {
//                        $subjectQuery->whereColumn('subjects.id', 'grade_subject_test.subject_id');
//                    });
//            }]);
//        }])->get();
//        $tests = Test::all();
//        $grades = Test::all();
//        dd($grades);

//        return TestResource::collection($grades)->resolve();
//        return  TestTypeResource::make($grades)->resolve();

//        $test = Test::with('questions')->where('subject_id', 10)->where('grade_id', 1)->get();  // Загружаем связанный TestType

        //выводим все тесты с количеством вопросов в каждом
        $test = Test::with('questions')->get();
        return TestResource::collection($test)->resolve();
    }


}
