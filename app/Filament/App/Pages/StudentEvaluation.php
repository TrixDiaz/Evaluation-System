<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluation as StudentEvaluationModel;
use App\Models\StudentEvaluationResponse;
use Filament\Pages\Page;

class StudentEvaluation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.student-evaluation';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $title = 'Student Evaluations';
    protected static ?int $navigationSort = 3;


    public $evaluations;

    public function mount()
    {
        $this->evaluations = StudentEvaluationModel::with('questions')->get();
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
        if ($this->hasSubmitted($evaluationId)) {
            return;
        }

        return redirect()->route('filament.app.pages.student-evaluation-form', ['evaluation' => $evaluationId]);
    }
}
