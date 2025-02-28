<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluation as StudentEvaluationModel;
use Filament\Pages\Page;

class StudentEvaluation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.student-evaluation';

    protected static ?string $navigationGroup = 'Evaluation';

    public $evaluations;

    public function mount()
    {
        $this->evaluations = StudentEvaluationModel::with('questions')->get();
    }
}
