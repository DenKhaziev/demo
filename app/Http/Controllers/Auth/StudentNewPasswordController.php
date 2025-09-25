<?php

namespace App\Http\Controllers\Auth;

use App\Models\Child;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class StudentNewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetStudentPassword', [
            'email' => $request->query('email'),
            'token' => $request->query('token'),
        ]);
    }

    public function store(Request $request)
    {
        // Валидация
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Проверяем наличие записи в таблице password_resets
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Недопустимый или просроченный токен.']);
        }

        // Находим ученика по email
        $student = Child::where('email', $request->email)->first();

        if (!$student) {
            return back()->withErrors(['email' => 'Ученик с таким email не найден.'])->withInput();
        }

        // Сохраняем новый пароль
        $student->password = Hash::make($request->password);
        $student->setRememberToken(Str::random(60));
        $student->save();

        // Удаляем токен сброса
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // переводим на логин
        return redirect()->route('student.login')->with('success', 'Пароль успешно обновлён!');
    }


}
