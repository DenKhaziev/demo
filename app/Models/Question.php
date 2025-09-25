<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $appends = ['image_url'];
    protected $guarded = [];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/images/' . $this->image_path)
            : null;
    }
}
