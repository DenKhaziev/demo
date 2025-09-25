<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    public $guarded = [];


    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

}
