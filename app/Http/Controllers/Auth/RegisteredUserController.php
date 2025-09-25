<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone',
            'child_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'grade_id' => 'required|integer|min:1|max:11',
            'attestation_type' => 'required|string|in:ПА,ГИА',
        ]);

        // Родитель
        $parent = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Генерируем пароль для ученика
        $studentPassword = Str::random(8);
        $parentPassword = $request->password;

        $student = Child::create([
            'user_id' => $parent->id,
            'name' => $request->child_name,
            'grade_id' => $request->grade_id,
            'attestation_type' => $request->attestation_type,
            'email' => 'student' . $parent->id . '@penaty.ru',
            'password' => Hash::make($studentPassword),
        ]);

        event(new Registered($parent));

        Auth::login($parent);
        $parent->notify(new \App\Notifications\StudentRegisteredNotification($student->email, $studentPassword, $request->email, $parentPassword));


        return redirect()->intended(auth()->user()->redirectAfterLogin());
    }
}
