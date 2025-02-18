<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

final class CopusSheet
{
    public static function schema(): array
    {
        return [
            Grid::make([
                Forms\Components\TextInput::make('observer_name')
                    ->required()
                    ->label('Observer Name')
                    ->maxLength(255)
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('observation_date')
                    ->required()
                    ->label('Observation Date')
                    ->columnSpan(1),
            ])
                ->columns([
                    'default' => 1,
                    'sm' => 2
                ]),

            Forms\Components\TextInput::make('course_name')
                ->required()
                ->label('Course Name')
                ->maxLength(255),

            Section::make('Time Segments')
                ->schema([
                    Tabs::make('Activities')
                        ->tabs([
                            Tab::make('Student Activities')
                                ->schema(self::getStudentActivitiesGrid()),

                            Tab::make('Instructor Activities')
                                ->schema(self::getInstructorActivitiesGrid()),
                        ]),

                    Forms\Components\Textarea::make('comments')
                        ->label('Comments')
                        ->placeholder('Enter any additional observations, explanations, difficulties, analogies, etc.')
                        ->rows(3)
                ])
        ];
    }

    private static function getStudentActivitiesGrid(): array
    {
        return [
            self::createActivityCheckbox('L', 'Listening to instructor/taking notes', 'Listening and note-taking activities'),
            self::createActivityCheckbox('Ind', 'Individual thinking/problem solving', 'Independent problem-solving work'),
            self::createActivityCheckbox('CG', 'Clicker group work', 'Working with clickers in groups'),
            self::createActivityCheckbox('WG', 'Working in groups', 'Group work on worksheet activities'),
            self::createActivityCheckbox('OG', 'Other group activity', 'Other types of group work'),
            self::createActivityCheckbox('AnQ', 'Student answering question', 'Students providing answers'),
            self::createActivityCheckbox('SQ', 'Student asks question', 'Students asking questions'),
            self::createActivityCheckbox('WC', 'Whole class discussion', 'Full class discussions'),
            self::createActivityCheckbox('Prd', 'Making predictions', 'Predictions about demos/experiments'),
            self::createActivityCheckbox('SP', 'Student presentation', 'Students presenting to class'),
            self::createActivityCheckbox('TQ', 'Test or quiz', 'Assessment activities'),
            self::createActivityCheckbox('W', 'Waiting', 'Waiting time'),
            self::createActivityCheckbox('O', 'Other', 'Other student activities')
        ];
    }

    private static function getInstructorActivitiesGrid(): array
    {
        return [
            self::createActivityCheckbox('Lec', 'Lecturing', 'Traditional lecture or presentation'),
            self::createActivityCheckbox('RtW', 'Real-time writing', 'Writing in real-time'),
            self::createActivityCheckbox('FUp', 'Follow-up/feedback', 'Following up on activities'),
            self::createActivityCheckbox('PQ', 'Posing question', 'Asking non-clicker questions'),
            self::createActivityCheckbox('CQ', 'Clicker question', 'Using clicker questions'),
            self::createActivityCheckbox('AnQ', 'Answering question', 'Responding to student questions'),
            self::createActivityCheckbox('MG', 'Moving through groups', 'Circulating among groups'),
            self::createActivityCheckbox('1o1', 'One-on-one discussion', 'Individual student interactions'),
            self::createActivityCheckbox('DV', 'Demo/video/simulation', 'Demonstrations and media'),
            self::createActivityCheckbox('Adm', 'Administration', 'Administrative tasks'),
            self::createActivityCheckbox('W', 'Waiting', 'Waiting time'),
            self::createActivityCheckbox('O', 'Other', 'Other instructor activities')
        ];
    }

    private static function createActivityCheckbox(string $key, string $label, string $hint): Forms\Components\Checkbox
    {
        return Forms\Components\Checkbox::make($key)
            ->label($label)
            ->helperText($hint)
            ->inline();
    }
}
