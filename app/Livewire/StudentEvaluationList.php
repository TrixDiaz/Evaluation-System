<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentEvaluation;

class StudentEvaluationList extends Component
{
    public function render()
    {
        return view('livewire.student-evaluation-list', [
            'evaluations' => StudentEvaluation::all()
        ]);
    }
}
