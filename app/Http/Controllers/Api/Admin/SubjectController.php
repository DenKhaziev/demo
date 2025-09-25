<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index() {
        $subjects = Subject::all();
        return SubjectResource::collection($subjects)->response();
}


}
