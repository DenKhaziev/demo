<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChildResource;
use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;

class AdminParentsController extends Controller
{
    // выгрузка всех не активных родителей с детьми, оставлю код закомментированным
    /*public function newParents() {
        $parents = User::whereHas('children', function ($query) {
            $query->where('is_payment', false);
        })->with('children')->get();
        return UserResource::collection($parents)->resolve();
    }*/
    public function newParents() {
        $parents = User::whereHas('children', function ($query) {
            $query
                ->where('is_payment', false);
        })->orderByDesc('id')->get();
        return UserResource::collection($parents)->resolve();
    }

    public function activeParents() {
        $parents = User::whereHas('children', function ($query) {
            $query
                ->where('is_payment', true);
        })->orderByDesc('id')->get();
        return UserResource::collection($parents)->resolve();
    }

    public function showChildren(User $user) {
        return ChildResource::collection($user->children)->resolve();
    }
}
