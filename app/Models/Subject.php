<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_subject_test')
            ->withPivot('test_id')
            ->with('tests');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

//    public function tests()
//    {
//        return $this->belongsToMany(Test::class, 'grade_subject_test', 'subject_id', 'test_id')
//            ->withPivot('grade_id');
//    }

//    public function tests()
//    {
//        return $this->hasMany(Test::class, 'id', 'pivot.test_id')
////            ->withPivot('grade_id')
////            ->with('questions') // не вывозит пк
//            ->withTimestamps();
//    }

    public function passedTests()
    {
        return $this->belongsToMany(PassedTest::class);
    }

//    public function getTestsAttribute()
//    {
//        return $this->pivot->test_id ? Test::find($this->pivot->test_id) : null;
//    }
}



