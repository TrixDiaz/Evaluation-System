<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

final class CopusSheet
{
    private static $timeSegments = [];

    public static function generateTimeSegments()
    {
        for ($i = 2; $i <= 58; $i += 2) {
            self::$timeSegments[] = "{$i}-" . ($i + 2);
        }
    }

    public static function schema(): array
    {
        self::generateTimeSegments();

        return [
            Grid::make([
                Forms\Components\TextInput::make('observer_name')
                    ->required()
                    ->label('Observer Name')
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('observation_date')
                    ->required()
                    ->label('Observation Date')
                    ->columnSpan(1),

                Forms\Components\TextInput::make('course_name')
                    ->required()
                    ->label('Course Name')
                    ->columnSpan(2),
            ])
                ->columns(2),

            Forms\Components\View::make('copus.observation-grid')
                ->viewData([
                    'timeSegments' => self::$timeSegments,
                    'studentActivities' => self::getStudentActivities(),
                    'instructorActivities' => self::getInstructorActivities(),
                ])
                // This ensures Alpine.js is available for the modals
                ->extraAttributes(['x-data' => '{}']),

            Forms\Components\Textarea::make('additional_comments')
                ->label('Additional Comments')
                ->placeholder('Enter any other general observations or notes about the session')
                ->rows(3)
        ];
    }

    private static function getStudentActivities(): array
    {
        return [
            'L' => 'Listening to instructor/taking notes',
            'Ind' => 'Individual thinking/problem solving',
            'CG' => 'Clicker group work',
            'WG' => 'Working in groups on worksheet',
            'OG' => 'Other group activity',
            'AnQ' => 'Student answering question',
            'SQ' => 'Student asks question',
            'WC' => 'Whole class discussion',
            'Prd' => 'Making predictions',
            'SP' => 'Student presentation',
            'TQ' => 'Test/Quiz',
            'W' => 'Waiting',
            'O' => 'Other'
        ];
    }

    private static function getInstructorActivities(): array
    {
        return [
            'Lec' => 'Lecturing',
            'RtW' => 'Real-time writing',
            'Fup' => 'Follow-up/feedback on activities',
            'PQ' => 'Posing non-clicker question',
            'CQ' => 'Clicker question',
            'AnQ' => 'Answering student question',
            'MG' => 'Moving through class guiding work',
            '1o1' => 'One-on-one extended discussion with student',
            'D/V' => 'Demo/video',
            'Adm' => 'Administrative tasks',
            'W' => 'Waiting',
            'O' => 'Other'
        ];
    }
}
