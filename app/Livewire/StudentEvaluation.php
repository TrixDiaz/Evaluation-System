<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;

class StudentEvaluation extends Component
{
    public StudentEvaluationModel $evaluation;
    public $answers = [];

    public function mount(StudentEvaluationModel $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function submit()
    {
        foreach ($this->answers as $questionId => $answer) {
            StudentEvaluationResponse::create([
                'student_evaluation_id' => $this->evaluation->id,
                'student_evaluation_question_id' => $questionId,
                'user_id' => auth()->id(),
                'answer' => $answer,
            ]);
        }

        $this->reset('answers');
        $this->dispatch('evaluation-submitted');
        session()->flash('message', 'Evaluation submitted successfully.');
    }

    public function render()
    {
        return view('livewire.student-evaluation', [
            'evaluation' => $this->evaluation
        ]);
    }
}
