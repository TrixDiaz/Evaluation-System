<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;

class StudentTableResult extends Component
{
    public $evaluationId;
    public $showDescriptions = true;
    public $selectedLegend = ''; // New property for the filter
    public $selectedProfessor = ''; // New property for professor filter
    public $selectedYear = ''; // New property for year filter

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

        // Apply filter if selected
        if (!empty($this->selectedLegend)) {
            $legendCounts = array_filter(
                $legendCounts,
                fn($key) => $key === $this->selectedLegend,
                ARRAY_FILTER_USE_KEY
            );
        }

        // Get professors for the filter dropdown
        $professors = User::whereHas('schedules')->distinct()->get(['id', 'name']);

        // Get years for the filter dropdown
        $years = Schedule::distinct()->orderBy('year', 'desc')->pluck('year');

        return view('livewire.student-table-result', [
            'legendCounts' => $legendCounts,
            'legendDescriptions' => $this->showDescriptions ? $this->legendDescriptions : [],
            'allLegends' => $this->legendDescriptions, // Pass all legends for the dropdown
            'professors' => $professors,
            'years' => $years
        ]);
    }

    // Method to update the selected legend
    public function updateLegendFilter($legend)
    {
        $this->selectedLegend = $legend;
    }

    // Method to update the selected professor
    public function updateProfessorFilter($professorId)
    {
        $this->selectedProfessor = $professorId;
    }

    // Method to update the selected year
    public function updateYearFilter($year)
    {
        $this->selectedYear = $year;
    }

    private function calculateLegendCounts()
    {
        $legendCounts = [];

        // Get evaluations
        $query = Evaluation::query();
        if ($this->evaluationId) {
            $query->where('id', $this->evaluationId);
        }

        // Apply professor filter if selected
        if (!empty($this->selectedProfessor)) {
            $query->whereHas('schedule', function ($q) {
                $q->where('professor_id', $this->selectedProfessor);
            });
        }

        // Apply year filter if selected
        if (!empty($this->selectedYear)) {
            $query->whereHas('schedule', function ($q) {
                $q->where('year', $this->selectedYear);
            });
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
