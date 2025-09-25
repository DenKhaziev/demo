<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Child;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index() {
        $certificates = Certificate::with('child.user')->where('is_has_a_certificate', false)->get();
        return response()->json($certificates);

//
    }
}
