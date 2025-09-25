<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminMainController extends Controller
{
    public function index()
    {
//        dd(auth()->user()->is_admin);
    return Inertia::render('Admin/AdminMain');
    }
}
