<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEvaluationResponse extends Model
{
    protected $fillable = [
        'student_evaluation_id',
        'student_eval_question_id',
        'user_id',
        'schedule_id',
        'year',
        'answer'
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(StudentEvaluation::class, 'student_evaluation_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(StudentEvaluationQuestion::class, 'student_eval_question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
