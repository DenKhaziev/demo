<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];

//    public function grades()
//    {
//        return $this->belongsToMany(Grade::class, 'grade_subject_test')
//            ->withPivot('subject_id')
//            ->withTimestamps();
//    }
//
//    public function subjects()
//    {
//        return $this->belongsToMany(Subject::class, 'grade_subject_test')
//            ->withPivot('grade_id')
//            ->withTimestamps();
//    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function type()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function passedTests()
    {
        return $this->hasMany(PassedTest::class);
    }
}
