<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'time',
        'semester',
        'is_active',
        'course_id',
        'professor_id',
        'subject_id',
        'room_id',
        'year',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function copusObservations()
    {
        return $this->hasMany(CopusObservation::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'schedule_user');
    }

    public function studentEvaluations(): BelongsToMany
    {
        return $this->belongsToMany(StudentEvaluation::class);
    }

    public function evaluations()
    {
        return $this->belongsToMany(StudentEvaluation::class, 'student_evaluation_schedules');
    }
}
