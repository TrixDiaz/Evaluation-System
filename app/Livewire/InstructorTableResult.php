<?php

namespace App\Livewire;

use App\Models\Evaluation;
use Livewire\Component;

class InstructorTableResult extends Component
{
    public $evaluationId;
    public $showDescriptions = true;

    // Legend descriptions for instructor activities
    private $legendDescriptions = [
        'Lec' => 'Lecturing',
        'PQ' => 'Posing Questions',
        'AnQ' => 'Answering Questions',
        'RtW' => 'Real-time Writing',
        'Fup' => 'Follow-up',
        'CQ' => 'Clarifying Questions',
        'MG' => 'Managing Groups',
        '1o1' => 'One-on-one Assistance',
        'D/V' => 'Demonstrating/Visualizing',
        'Adm' => 'Administrative Tasks',
        'W' => 'Writing',
        'O' => 'Other'
    ];

    public function mount($evaluationId = null, $showDescriptions = true)
    {
        $this->evaluationId = $evaluationId;
        $this->showDescriptions = $showDescriptions;
    }

    public function render()
    {
        $legendCounts = $this->calculateLegendCounts();

        return view('livewire.instructor-table-result', [
            'legendCounts' => $legendCounts,
            'legendDescriptions' => $this->showDescriptions ? $this->legendDescriptions : []
        ]);
    }

    private function calculateLegendCounts()
    {
        $legendCounts = [];

        // Get evaluations
        $query = Evaluation::query();
        if ($this->evaluationId) {
            $query->where('id', $this->evaluationId);
        }
        $evaluations = $query->get();

        // Process each evaluation
        foreach ($evaluations as $evaluation) {
            $instructorActivities = $evaluation->instructor_activities;

            // Flatten the activities
            foreach ($instructorActivities as $timeInterval => $activities) {
                foreach ($activities as $activity) {
                    if (!isset($legendCounts[$activity])) {
                        $legendCounts[$activity] = 0;
                    }
                    $legendCounts[$activity]++;
                }
            }
        }

        // Sort by legend name
        ksort($legendCounts);

        return $legendCounts;
    }
}
