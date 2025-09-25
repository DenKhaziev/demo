<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassedTest extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'answers' => 'array',
    ];

    public function child() {
        return $this->belongsTo(Child::class);
    }
    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    public function type() {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
    public function test() {
        return $this->belongsTo(Test::class);
    }
}
