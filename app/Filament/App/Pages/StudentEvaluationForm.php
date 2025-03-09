<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentEvaluationForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    protected static string $view = 'filament.app.pages.student-evaluation-form';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static bool $shouldRegisterNavigation = false;

    public ?StudentEvaluationModel $evaluation = null;
    public $answers = [];
    public $agreed = false;
    public $currentStep = 1;
    public $schedule = '';
    public $year = '';

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        return route('filament.app.pages.student-evaluation-form', ['evaluation' => $parameters['evaluation']], $isAbsolute);
    }

    public function mount(): void
    {
        $evaluationId = request()->query('evaluation');
        $this->evaluation = StudentEvaluationModel::with('questions')->findOrFail($evaluationId);

        // Get the schedule_id passed from the URL
        $scheduleId = request()->query('schedule_id');

        if ($scheduleId) {
            // Verify if schedule belongs to user
            $userHasSchedule = DB::table('schedule_user')
                ->where('user_id', auth()->id())
                ->where('schedule_id', $scheduleId)
                ->exists();

            if ($userHasSchedule) {
                $this->schedule = $scheduleId;
            } else {
                Notification::make()
                    ->title('Invalid Schedule')
                    ->warning()
                    ->send();
                $this->redirect(StudentEvaluation::getUrl());
            }
        } else {
            // Get schedule from student_evaluation_schedule table
            $scheduleData = StudentEvaluationSchedule::where('student_evaluation_id', $evaluationId)
                ->first();

            if ($scheduleData) {
                $this->schedule = $scheduleData->schedule_id;
            } else {
                Notification::make()
                    ->title('No schedule found for this evaluation')
                    ->warning()
                    ->send();
                $this->redirect(StudentEvaluation::getUrl());
            }
        }

        // Check if user has already submitted
        $hasSubmitted = StudentEvaluationResponse::where([
            'student_evaluation_id' => $this->evaluation->id,
            'user_id' => auth()->id(),
        ])->exists();

        if ($hasSubmitted) {
            Notification::make()
                ->title('You have already submitted this evaluation')
                ->warning()
                ->send();

            $this->redirect(StudentEvaluation::getUrl());
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'agreed' => 'accepted',
            ], [
                'agreed.accepted' => 'You must agree to the terms before proceeding.',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'schedule' => 'required',
                'year' => 'required',
            ]);
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate([
            'schedule' => 'required',
            'year' => 'required',
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);

        foreach ($this->answers as $questionId => $answer) {
            StudentEvaluationResponse::create([
                'student_evaluation_id' => $this->evaluation->id,
                'student_eval_question_id' => $questionId, // Add this line
                'user_id' => auth()->id(),
                'schedule_id' => $this->schedule,
                'year' => $this->year,
                'answer' => $answer,
            ]);
        }

        Notification::make()
            ->title('Evaluation submitted successfully')
            ->success()
            ->send();

        return redirect()->to(StudentEvaluation::getUrl());
    }
}
