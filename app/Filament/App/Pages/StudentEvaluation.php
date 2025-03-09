<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;
use App\Models\StudentEvaluationSchedule;
use App\Models\Schedule;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class StudentEvaluation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.student-evaluation';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $title = 'Student Evaluations';
    protected static ?int $navigationSort = 3;


    public $evaluations;
    public $totalEvaluations = 0;
    public $completedEvaluations = 0;
    public $completionPercentage = 0;

    public function mount()
    {

        // Get the authenticated user's schedules from user_schedule table
        $userScheduleIds = DB::table('schedule_user')
            ->where('user_id', auth()->id())
            ->pluck('schedule_id');

        // If user has no schedules, return empty collection
        if ($userScheduleIds->isEmpty()) {
            $this->evaluations = collect();
            return;
        }

        // Get evaluation IDs from student_evaluation_schedule that match user's schedules
        $evaluationIds = StudentEvaluationSchedule::whereIn('schedule_id', $userScheduleIds)
            ->pluck('student_evaluation_id');

        // Get evaluations with their questions
        $this->evaluations = StudentEvaluationModel::with('questions')
            ->whereIn('id', $evaluationIds)
            ->get();

        // After getting evaluations, calculate statistics
        $this->totalEvaluations = $this->evaluations->count();
        $this->completedEvaluations = $this->evaluations
            ->filter(fn($evaluation) => $this->hasSubmitted($evaluation->id))
            ->count();

        $this->completionPercentage = $this->totalEvaluations > 0
            ? round(($this->completedEvaluations / $this->totalEvaluations) * 100)
            : 0;
    }

    public function hasSubmitted($evaluationId)
    {
        return StudentEvaluationResponse::where([
            'student_evaluation_id' => $evaluationId,
            'user_id' => auth()->id(),
        ])->exists();
    }
    public function startEvaluation($evaluationId)
    {
        $evaluation = StudentEvaluationModel::find($evaluationId);

        // Check if evaluation is active and not submitted
        if (!$evaluation->is_active || $this->hasSubmitted($evaluationId)) {
            return;
        }

        // Find the correct schedule_id from the student_evaluation_schedule table
        $schedule = StudentEvaluationSchedule::where('student_evaluation_id', $evaluationId)->first();

        // If no schedule is found, return or handle this situation
        if (!$schedule) {
            return; // Handle this error as needed
        }

        $scheduleId = $schedule->schedule_id;

        return redirect()->route('filament.app.pages.student-evaluation-form', [
            'evaluation' => $evaluationId,
            'schedule_id' => $scheduleId,
        ]);
    }
}
