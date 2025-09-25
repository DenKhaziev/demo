<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChildResource;
use App\Http\Resources\TestResource;
use App\Models\Child;
use App\Models\PassedTest;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\Request;

class AdminChildrenController extends Controller
{
    public function newChildren() {
        $children = Child::all()->where('is_payment', false);
        return ChildResource::collection($children)->resolve();
    }

    public function activeChildren() {
        $children = Child::all()->where('is_payment', true);
        return ChildResource::collection($children)->resolve();
    }

    public function withCertificateChildren()
    {
        $children = Child::all()->where('is_has_a_certificate', true);
        return ChildResource::collection($children)->resolve();
    }

    public function showChild(Child $child) {

        [$remainingTests, $passedTests] = TestService::showAllTests($child);

        return  response()->json([
            'passedTests' => ChildResource::make($passedTests)->resolve(),
            'remainingTests' => ChildResource::make($remainingTests)->resolve()
        ]);
    }

}
