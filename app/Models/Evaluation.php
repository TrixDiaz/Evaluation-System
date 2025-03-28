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
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'observation_date' => 'date',
        'student_activities' => 'array',
        'instructor_activities' => 'array',
    ];

    public function getStudentActivitiesAttribute($value)
    {
        return json_decode($value) ?? [];
    }

    public function getInstructorActivitiesAttribute($value)
    {
        return json_decode($value) ?? [];
    }

    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
