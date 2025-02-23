<?php

namespace App\Services;

use Filament\Forms;

final class Evaluation
{
    const MINUTES = ['0-2', '3-4', '5-6', '7-8', '9-10'];

    const STUDENT_CODES = [
        'L' => 'Listening to instructor',
        'Ind' => 'Individual thinking/problem solving',
        'CG' => 'Clicker Group Discussion',
        'WG' => 'Working in groups',
        'OG' => 'Other group activities',
        'AnQ' => 'Answering question',
        'SQ' => 'Student question',
        'WC' => 'Whole class discussion',
        'Prd' => 'Making predictions',
        'SP' => 'Student presentation',
        'T/Q' => 'Test/Quiz',
        'W' => 'Waiting',
        'O' => 'Other'
    ];

    const INSTRUCTOR_CODES = [
        'Lec' => 'Lecturing',
        'RtW' => 'Real-time writing',
        'Fup' => 'Follow-up/feedback on clicker question or activity',
        'PQ' => 'Posing non-clicker question',
        'CQ' => 'Clicker question',
        'AnQ' => 'Answering student question',
        'MG' => 'Moving through group work',
        '1o1' => 'One-on-one extended discussion',
        'D/V' => 'Demo/video',
        'Adm' => 'Administrative task',
        'W' => 'Waiting',
        'O' => 'Other'
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
                ]),

            Forms\Components\Section::make('COPUS Observation Matrix')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Section::make('1. Students doing')
                                ->schema([
                                    Forms\Components\CheckboxList::make('student_activities')
                                        ->gridDirection('row')
                                        ->columns(count(self::STUDENT_CODES) + 1)
                                        ->options(self::generateOptionsGrid(self::STUDENT_CODES))
                                        ->descriptions([
                                            'L' => 'Listening to instructor',
                                            'Ind' => 'Individual thinking/problem solving',
                                            // ... add descriptions for all codes
                                        ]),
                                ]),

                            Forms\Components\Section::make('2. Instructor doing')
                                ->schema([
                                    Forms\Components\CheckboxList::make('instructor_activities')
                                        ->gridDirection('row')
                                        ->columns(count(self::INSTRUCTOR_CODES) + 1)
                                        ->options(self::generateOptionsGrid(self::INSTRUCTOR_CODES))
                                        ->descriptions([
                                            'Lec' => 'Lecturing',
                                            'RtW' => 'Real-time writing',
                                            // ... add descriptions for all codes
                                        ]),
                                ]),
                        ]),
                ]),

            Forms\Components\Section::make('Additional Information')
                ->schema([
                    Forms\Components\Textarea::make('additional_comments')
                        ->label('Comments')
                        ->placeholder('Enter any additional comments, explanations of analogies, etc.')
                        ->rows(3),
                ]),
        ];
    }

    private static function generateOptionsGrid(array $codes): array
    {
        $options = [];
        foreach (self::MINUTES as $minute) {
            foreach ($codes as $code => $description) {
                $options["{$minute}.{$code}"] = $code;
            }
        }
        return $options;
    }
}
