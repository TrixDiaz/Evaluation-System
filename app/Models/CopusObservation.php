<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CopusObservation extends Model
{
    protected $fillable = [
        'schedule_id',
        'observation_number',
        'observer_name',
        'observation_date',
        'course_name',
        'student_activities',
        'instructor_activities',
        'comments',
        'additional_comments',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'student_activities' => 'array',
        'instructor_activities' => 'array',
        'comments' => 'array',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
