<?php

namespace App\Services;

use Filament\Forms;

final class Evaluation
{
    const MINUTES = ['0-2', '3-5', '6-8', '9-11', '12-14']; // Define minute intervals

    // Function to generate checkbox options with minute intervals
    private static function generateOptionsGrid(array $codes): array
    {
        $options = [];
        foreach (self::MINUTES as $minute) {
            foreach ($codes as $code => $description) {
                $options["{$minute}.{$code}"] = "{$minute} min - {$description}"; // Label includes minute interval
            }
        }
        return $options;
    }

    const STUDENT_CODES = [
        'L' => 'L',
        'Ind' => 'Ind',
        'CG' => 'CG',
        'WG' => 'WG',
        'OG' => 'OG',
        'AnQ' => 'AnQ',
        'SQ' => 'SQ',
        'WC' => 'WC',
        'Prd' => 'Prd',
        'SP' => 'SP',
        'T/Q' => 'T/Q',
        'W' => 'W',
        'O' => 'O'
    ];

    const INSTRUCTOR_CODES = [
        'Lec' => 'Lec',
        'RtW' => 'RtW',
        'Fup' => 'Fup',
        'PQ' => 'PQ',
        'CQ' => 'CQ',
        'AnQ' => 'AnQ',
        'MG' => 'MG',
        '1o1' => '1o1',
        'D/V' => 'D/V',
        'Adm' => 'Adm',
        'W' => 'W',
        'O' => 'O'
    ];

    public static function schema($record): array
    {
        $authUser = auth()->user();

        return [
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Placeholder::make('observer')
                        ->label('Observer')
                        ->content($authUser->name),
                    Forms\Components\Placeholder::make('observation_date')
                        ->label('Observation Date')
                        ->content(now()->format('Y-m-d H:i')),
                    Forms\Components\Placeholder::make('course_name')
                        ->label('Course Name')
                        ->content($record->course?->name),
                    Forms\Components\Placeholder::make('professor_name')
                        ->label('Professor Name')
                        ->content($record->professor?->name),
                    Forms\Components\Placeholder::make('room')
                        ->label('Room')
                        ->content($record->room?->name),
                    Forms\Components\Placeholder::make('semester_year')
                        ->label('Semester & Year')
                        ->content("{$record->semester} - {$record->year}"),
                ])
                ->columns(2)
                ->collapsed()
                ->collapsible(),

            Forms\Components\Section::make('COPUS Observation Matrix')
                ->schema([
                    Forms\Components\Tabs::make()
                        ->schema([
                            Forms\Components\Tabs\Tab::make('1. Students doing')
                                ->schema([
                                    Forms\Components\CheckboxList::make('student_activities')
                                        ->label('Student Activities per Minute Interval')
                                        ->gridDirection('row')
                                        ->columns(13) // Adjust columns for better display
                                        ->options(self::generateOptionsGrid(self::STUDENT_CODES))
                                        ->bulkToggleable(),
                                ]),

                            Forms\Components\Tabs\Tab::make('2. Instructor doing')
                                ->schema([
                                    Forms\Components\CheckboxList::make('instructor_activities')
                                        ->label('Instructor Activities per Minute Interval')
                                        ->gridDirection('row')
                                        ->columns(13)
                                        ->options(self::generateOptionsGrid(self::INSTRUCTOR_CODES))
                                        ->bulkToggleable(),
                                ]),
                        ]),
                ]),

            Forms\Components\Section::make('Additional Information')
                ->schema([
                    Forms\Components\Textarea::make('additional_comments')
                        ->label('Comments')
                        ->placeholder('Enter any additional comments, explanations of analogies, etc.')
                        ->rows(3),
                ])
                ->collapsed()
                ->collapsible(),
        ];
    }
}
