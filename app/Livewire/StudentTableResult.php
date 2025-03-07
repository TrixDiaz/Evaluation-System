<?php

namespace App\Livewire;

use App\Models\Evaluation;
use Livewire\Component;

class StudentTableResult extends Component
{
    public $evaluationId;
    public $showDescriptions = true;

    // Legend descriptions
    private $legendDescriptions = [
        'L' => 'Listening',
        'Ind' => 'Individual Work',
        'WG' => 'Working in Groups',
        'CG' => 'Class Group Work',
        'OG' => 'Other Group Activities',
        'AnQ' => 'Answering Questions',
        'SQ' => 'Student Questions',
        'WC' => 'Written Classwork',
        'SP' => 'Student Presentation',
        'Prd' => 'Prediction Activities',
        'T/Q' => 'Thinking/Questioning',
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

        return view('livewire.student-table-result', [
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
            $studentActivities = $evaluation->student_activities;

            // Flatten the activities
            foreach ($studentActivities as $timeInterval => $activities) {
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
