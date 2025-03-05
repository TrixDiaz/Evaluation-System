<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluationSchedule extends Model
{
    protected $fillable = [
        'student_evaluation_id',
        'schedule_id'
    ];
}
