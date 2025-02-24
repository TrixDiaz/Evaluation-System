<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_type',
        'question_content',
        'answer_content',
        'correct_answer',
        'rating_correct_answer',
        'points',
        'order'
    ];

    protected $casts = [
        'question_content' => 'array',
        'answer_content' => 'array',
        'points' => 'integer',
        'rating_correct_answer' => 'integer'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
