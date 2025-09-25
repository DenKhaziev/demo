<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subject_test')
            ->withPivot('test_id');
//            ->with('tests');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

//    public function tests()
//    {
//        return $this->belongsToMany(Test::class, 'grade_subject_test')
//            ->withPivot('subject_id');
//    }

    public function children()
    {
        return $this->hasMany(Test::class);
    }

    public function passedTests()
    {
        return $this->belongsToMany(PassedTest::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
