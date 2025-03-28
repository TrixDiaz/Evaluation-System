<?php

namespace App\Livewire;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Evaluation;

class InstructorActivities extends ApexChartWidget
{
    protected static ?string $chartId = 'instructorActivities';
    protected static ?string $heading = 'Overall Instructor Evaluation Summary';

    protected function getOptions(): array
    {
        // Get all evaluations that have instructor_activities
        $evaluations = Evaluation::whereNotNull('instructor_activities')->get();

        // Count frequency of each activity across all evaluations
        $activityCounts = [];
        foreach ($evaluations as $evaluation) {
            $activities = $evaluation->instructor_activities ?? [];

            // Flatten and count activities from all time slots
            foreach ($activities as $timeSlot => $activityList) {
                foreach ($activityList as $activity) {
                    $activityCounts[$activity] = ($activityCounts[$activity] ?? 0) + 1;
                }
            }
        }

        // Sort activities by count in descending order
        arsort($activityCounts);

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 400,
            ],
            'series' => array_values($activityCounts),
            'labels' => array_keys($activityCounts),
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
                'position' => 'right',
            ],
            'tooltip' => [
                'enabled' => true,
                'y' => [
                    'formatter' => 'function(value) { return value + " times" }'
                ]
            ],
        ];
    }
}
