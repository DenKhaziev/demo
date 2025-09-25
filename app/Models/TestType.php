<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $appends = ['label'];


    public function tests() {
        return $this->hasMany(Test::class);
    }

    public function passedTests()
    {
        return $this->belongsToMany(PassedTest::class);
    }

    public function getLabelAttribute(): string
    {
        return match ($this->type) {
            'required' => 'Обязательный',
            'optional' => 'Дополнительный (не обязательный)',
            'demo' => 'Демо',
            default => ucfirst($this->type), // запасной вариант
        };
    }

}
