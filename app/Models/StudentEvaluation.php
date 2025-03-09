<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'schedule_id',
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function questions(): HasMany
    {
        return $this->hasMany(StudentEvaluationQuestion::class, 'student_evaluation_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StudentEvaluationResponse::class, 'student_evaluation_id');
    }

    public function schedule(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'student_evaluation_schedules');
    }
}
