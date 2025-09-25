<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

//class Child extends Model
class Child extends Authenticatable

{
    use CanResetPasswordTrait;
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'password',
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }
    public function userInfo() {
        return $this->hasOne(UserInfo::class, 'child_id', 'id');
    }

    public function grade () {
        return $this->belongsTo(Grade::class);
    }

    public function passedTests() {
        return $this->hasMany(PassedTest::class);
    }

    public function documents() {
        return $this->hasMany(DocumentByGrade::class);
    }

    public function certificate(): hasOne
    {
        return $this->hasOne(Certificate::class, 'child_id');
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            Ticket::class,   // конечная модель
            User::class,     // промежуточная модель
            'id',        // user.id — локальный ключ на промежуточную модель
            'user_id',   // ticket.user_id — внешний ключ в конечной модели
            'user_id',   // child.user_id — внешний ключ в промежуточной модели
            'id'         // user.id — локальный ключ в промежуточной модели
        );
    }

    public function tests()
    {
        return $this->hasManyThrough(
            Test::class,  // Конечная таблица (куда мы хотим прийти)
            Grade::class, // Промежуточная таблица (через что связываем)
            'id',         // Локальный ключ в таблице grades (id класса)
            'grade_id',   // Внешний ключ в tests (grade_id в тестах)
            'grade_id',   // Локальный ключ в children (grade_id ученика)
            'id'          // Внешний ключ в grades (id класса)
        );
    }

    public function getAuthIdentifierName()
    {
        return 'email';
    }
}
