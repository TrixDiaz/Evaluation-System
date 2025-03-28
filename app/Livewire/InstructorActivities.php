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
        $evaluation = Evaluation::first();
        $activities = $evaluation?->instructor_activities ?? [];

        // Count frequency of each activity
        $activityCounts = [];
        foreach ($activities as $timeSlot => $activityList) {
            foreach ($activityList as $activity) {
                $activityCounts[$activity] = ($activityCounts[$activity] ?? 0) + 1;
            }
        }

        // Provide default data if empty
        if (empty($activityCounts)) {
            return [
                'chart' => [
                    'type' => 'donut',
                    'height' => 400,
                ],
                'series' => [0],
                'labels' => ['No Data Available'],
                'legend' => [
                    'labels' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ];
        }

        // Return actual data
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
            ],
        ];
    }
}
