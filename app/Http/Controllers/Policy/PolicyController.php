<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolicyController extends Controller
{
    public function index()
    {
        return Inertia::render('Policy/Policy');
    }
    public function familyEdu()
    {
        return Inertia::render('Policy/SOEasy');
    }
}
