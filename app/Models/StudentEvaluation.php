<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'title',
        'description'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(StudentEvaluationQuestion::class, 'student_evaluation_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StudentEvaluationResponse::class, 'student_evaluation_id');
    }
}
