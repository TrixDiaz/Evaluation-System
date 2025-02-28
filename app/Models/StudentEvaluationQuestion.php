<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEvaluationQuestion extends Model
{
    protected $fillable = [
        'student_evaluation_id',
        'question',
        'type',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function studentEvaluation(): BelongsTo
    {
        return $this->belongsTo(StudentEvaluation::class, 'student_evaluation_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StudentEvaluationResponse::class, 'student_eval_question_id');
    }
}
