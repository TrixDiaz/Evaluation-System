<?php

namespace App\Livewire;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Evaluation;

class InstructorActivities extends ApexChartWidget
{
    protected static ?string $chartId = 'instructorActivities';
    protected static ?string $heading = 'Instructor Activities';

    protected function getOptions(): array
    {
        $activities = Evaluation::first()->instructor_activities ?? [];

        // Count frequency of each activity
        $activityCounts = [];
        foreach ($activities as $timeSlot => $activityList) {
            foreach ($activityList as $activity) {
                $activityCounts[$activity] = ($activityCounts[$activity] ?? 0) + 1;
            }
        }

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
