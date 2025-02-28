<?php

namespace App\Filament\App\Resources\StudentEvaluationResource\Pages;

use App\Filament\App\Resources\StudentEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentEvaluation extends EditRecord
{
    protected static string $resource = StudentEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
