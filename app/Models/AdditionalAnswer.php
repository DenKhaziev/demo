<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalAnswer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(AdditionalQuestion::class);
    }
}
