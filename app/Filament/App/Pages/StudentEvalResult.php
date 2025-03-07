<?php

namespace App\Filament\App\Pages;

use App\Models\StudentEvaluationResponse;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class StudentEvalResult extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.app.pages.student-eval-result';
    protected static ?string $navigationGroup = 'Evaluation';
    protected static ?string $title = 'Evaluation Summary';
    protected static ?int $navigationSort = 4;

    public function getSummaryData()
    {
        return StudentEvaluationResponse::query()
            ->join('student_evaluations', 'student_evaluation_responses.student_evaluation_id', '=', 'student_evaluations.id')
            ->select([
                'student_evaluation_responses.year',
                'student_evaluations.title as evaluation_title',
                DB::raw('COUNT(DISTINCT student_evaluation_responses.user_id) as total_respondents'),
                DB::raw('COUNT(*) as total_responses'),
                DB::raw('AVG(CASE WHEN student_evaluation_responses.answer REGEXP \'^[0-9]+$\' 
                    THEN CAST(student_evaluation_responses.answer AS DECIMAL) END) as average_rating')
            ])
            ->groupBy('student_evaluation_responses.year', 'student_evaluations.title')
            ->orderBy('student_evaluation_responses.year', 'desc')
            ->get();
    }

    public function getActivityLegendCounts()
    {
        $responses = Evaluation::whereNotNull('student_activities')
            ->pluck('student_activities');

        $allActivities = [];
        foreach ($responses as $response) {
            $activities = json_decode($response, true);
            if (!$activities) continue;

            // Flatten all time slot arrays into a single array
            foreach ($activities as $timeSlot => $acts) {
                $allActivities = array_merge($allActivities, $acts);
            }
        }

        // Count occurrences of each activity
        $activityCounts = array_count_values($allActivities);
        // Sort alphabetically by legend
        ksort($activityCounts);

        return $activityCounts;
    }
}
