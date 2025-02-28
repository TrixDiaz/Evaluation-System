<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class StudentEvaluationForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
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
                'student_evaluation_question_id' => $questionId,
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
