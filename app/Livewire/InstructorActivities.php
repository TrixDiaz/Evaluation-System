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

        if (!$evaluation || empty($evaluation->instructor_activities)) {
            return $this->getEmptyOptions();
        }

        // Count frequency of each activity across all time slots
        $activityCounts = [];
        foreach ($evaluation->instructor_activities as $timeSlot => $activities) {
            if (is_array($activities)) {
                foreach ($activities as $activity) {
                    if (!isset($activityCounts[$activity])) {
                        $activityCounts[$activity] = 0;
                    }
                    $activityCounts[$activity]++;
                }
            }
        }

        if (empty($activityCounts)) {
            return $this->getEmptyOptions();
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
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%'
                    ]
                ]
            ],
        ];
    }

    private function getEmptyOptions(): array
    {
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
}
