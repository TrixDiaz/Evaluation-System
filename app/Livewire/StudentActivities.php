<?php

namespace App\Livewire;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Evaluation;

class StudentActivities extends ApexChartWidget
{
    protected static ?string $chartId = 'studentActivities';
    protected static ?string $heading = 'Overall Student Evaluation Summary';

    protected function getOptions(): array
    {
        $activities = Evaluation::first()->student_activities ?? [];

        // Count frequency of each activity
        $activityCounts = [];
        foreach ($activities as $timeSlot => $activityList) {
            foreach ($activityList as $activity) {
                $activityCounts[$activity] = ($activityCounts[$activity] ?? 0) + 1;
            }
        }

        return [
            'chart' => [
                'type' => 'pie',
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
