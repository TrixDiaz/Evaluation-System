<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\User;
use App\Models\Schedule;
use Livewire\Component;

class InstructorTableResult extends Component
{
    public $evaluationId;
    public $showDescriptions = true;

    // New filter properties
    public $selectedLegend = '';
    public $selectedProfessor = '';
    public $selectedYear = '';

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

        // Legend filter
        if (!empty($this->selectedLegend)) {
            $legendCounts = array_filter(
                $legendCounts,
                fn($key) => $key === $this->selectedLegend,
                ARRAY_FILTER_USE_KEY
            );
        }

        // Get professors
        $professors = User::whereHas('schedules')->distinct()->get(['id', 'name']);
        // Get years
        $years = Schedule::distinct()->orderBy('year', 'desc')->pluck('year');

        return view('livewire.instructor-table-result', [
            'legendCounts' => $legendCounts,
            'legendDescriptions' => $this->showDescriptions ? $this->legendDescriptions : [],
            'allLegends' => $this->legendDescriptions, // For dropdown
            'professors' => $professors,
            'years' => $years
        ]);
    }

    private function calculateLegendCounts()
    {
        $legendCounts = [];

        $query = Evaluation::query();
        // Filter by evaluationId if provided
        if ($this->evaluationId) {
            $query->where('id', $this->evaluationId);
        }

        // Filter by professor
        if (!empty($this->selectedProfessor)) {
            $query->whereHas('schedule', function ($q) {
                $q->where('professor_id', $this->selectedProfessor);
            });
        }

        // Filter by year
        if (!empty($this->selectedYear)) {
            $query->whereHas('schedule', function ($q) {
                $q->where('year', $this->selectedYear);
            });
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
