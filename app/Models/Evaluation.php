<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'dean_id',
        'schedule_id',
        'observation_date',
        'additional_comments',
        'student_activities',
        'evaluation_type',
        'instructor_activities',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'student_activities' => 'array',
        'instructor_activities' => 'array',
    ];

    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
