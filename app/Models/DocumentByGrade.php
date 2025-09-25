<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentByGrade extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];
    protected $table = 'documents_by_grades';

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
