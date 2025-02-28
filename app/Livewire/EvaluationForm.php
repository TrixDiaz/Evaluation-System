<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use Livewire\Component;

class EvaluationForm extends Component
{
    public Evaluation $evaluation;
    public $answers = [];

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function submit()
    {
        foreach ($this->answers as $questionId => $answer) {
            EvaluationResponse::create([
                'evaluation_id' => $this->evaluation->id,
                'evaluation_question_id' => $questionId,
                'user_id' => auth()->id(),
                'answer' => $answer,
            ]);
        }

        session()->flash('message', 'Evaluation submitted successfully.');
        return redirect()->route('evaluations.index');
    }

    public function render()
    {
        return view('livewire.evaluation-form');
    }
}
