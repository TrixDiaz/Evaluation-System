<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;
use Filament\Notifications\Notification;

class StudentEvaluation extends Component
{
    public StudentEvaluationModel $evaluation;
    public $answers = [];
    public $agreed = false;
    public $currentStep = 1;
    public $schedule = '';
    public $year = '';

    public function mount(StudentEvaluationModel $evaluation)
    {
        $this->evaluation = $evaluation;
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

    public function submit()
    {
        $this->validate([
            'schedule' => 'required',
            'year' => 'required',
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

        $this->reset('answers');
        $this->dispatch('evaluation-submitted');

        Notification::make()
            ->title('Evaluation submitted successfully')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.student-evaluation', [
            'evaluation' => $this->evaluation,
            'schedules' => \App\Models\Schedule::all()
        ]);
    }
}
