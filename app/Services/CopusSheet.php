<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

final class CopusSheet
{
    private static $timeSegments = [];

    public static function schema(string $observerName): array
    {
        return [
            Grid::make('')
                ->schema([
                    Grid::make([
                        Forms\Components\TextInput::make('data.observer_name')
                            ->required()
                            ->label('Observer Name')
                            ->default($observerName)
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('data.observation_date')
                            ->required()
                            ->label('Observation Date')
                            ->default(now())
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('data.course_name')
                            ->required()
                            ->label('Course Name')
                            ->default(fn($livewire) => $livewire->record->course?->name ?? '')
                            ->disabled()
                            ->columnSpan(2),
                    ])->columns(2),

                    Forms\Components\Textarea::make('data.additional_comments')
                        ->label('Additional Comments')
                        ->placeholder('Enter any other general observations or notes about the session')
                        ->rows(3),

                    Forms\Components\Repeater::make('student_activities')
                        ->label('Student Activities')
                        ->schema([
                            Forms\Components\TextInput::make('activity')
                                ->label('Activity Description')
                                ->required(),
                            Forms\Components\TextInput::make('count')
                                ->label('Count')
                                ->required(),
                        ])
                        ->columns(2)
                        ->createItemButtonLabel('Add Student Activity'),

                    Forms\Components\Repeater::make('instructor_activities')
                        ->label('Instructor Activities')
                        ->schema([
                            Forms\Components\TextInput::make('activity')
                                ->label('Activity Description')
                                ->required(),
                            Forms\Components\TextInput::make('count')
                                ->label('Count')
                                ->required(),
                        ])
                        ->columns(2)
                        ->createItemButtonLabel('Add Instructor Activity'),
                ]),
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
