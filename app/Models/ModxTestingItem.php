<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModxTestingItem extends Model
{
    // Указываем таблицу вручную, если имя не соответствует соглашению
    protected $table = 'modx_testing_items';

    // Если в таблице нет timestamps (created_at, updated_at)
    public $timestamps = false;

    // Приведение атрибута questions к массиву
    protected $casts = [
        'questions' => 'array',
        'questions_peresdach' => 'array',
    ];
}
