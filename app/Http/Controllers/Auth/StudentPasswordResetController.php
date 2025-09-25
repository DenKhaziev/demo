<?php
namespace App\Http\Controllers\Auth;

use App\Models\Child;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Notifications\StudentPasswordResetLink;
use Inertia\Inertia;
use App\Http\Controllers\Controller;


class StudentPasswordResetController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/ForgotStudentPassword', [
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Найти ученика по "фейковому" email
        $student = Child::where('email', $request->input('email'))->first();

        if (!$student || !$student->user) {
            return back()->withErrors(['email' => 'Ученик не найден.']);
        }

        // Генерация токена для сброса пароля (как обычно)
        $token = Password::broker('children')->createToken($student);

        // Уведомляем родителя (user — это родитель ученика)
        $student->user->notify(new StudentPasswordResetLink($student, $token));

        return back()->with('status', 'Ссылка для сброса пароля отправлена родителю на email.');
    }

    
}

