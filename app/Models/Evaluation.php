<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'observer_name',
        'observation_date',
        'course_name',
        'additional_comments',
        'student_activities',
        'instructor_activities',
    ];
}
