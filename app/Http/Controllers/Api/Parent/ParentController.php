<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChildResource;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\User;
use App\Services\TestService;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index() {
        $userId = 3; // подставить id авторизованного родителя
        $children = User::with('children')->find($userId);
        return UserResource::make($children)->resolve();
    }

    public function showChild(Child $child) {

        [$remainingTests, $passedTests] = TestService::showAllTests($child);

        return response()->json([
            'passedTests' => ChildResource::make($passedTests)->resolve(),
            'remainingTests' => ChildResource::make($remainingTests)->resolve(),
        ]);
    }
}
