<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEvaluationResponse extends Model
{
    protected $fillable = [
        'student_evaluation_id',
        'student_evaluation_question_id',
        'user_id',
        'answer'
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(StudentEvaluation::class, 'student_evaluation_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(StudentEvaluationQuestion::class, 'student_evaluation_question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
