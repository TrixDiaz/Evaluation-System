<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'header_content',
        'footer_content',
        'time_limit',
        'is_published'
    ];

    protected $casts = [
        'header_content' => 'array',
        'footer_content' => 'array',
        'is_published' => 'boolean'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(QuizCompletion::class);
    }
}
