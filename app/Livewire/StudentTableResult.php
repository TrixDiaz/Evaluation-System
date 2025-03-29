<?php

namespace App\Livewire;

use App\Models\Evaluation;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;
use Filament\Facades\Filament;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;

class StudentTableResult extends Component
{
    public $evaluationId;
    public $showDescriptions = true;
    public $selectedLegend = ''; // New property for the filter
    public $selectedProfessor = ''; // New property for student filter
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

        if (!empty($this->selectedLegend)) {
            $legendCounts = array_filter(
                $legendCounts,
                fn($key) => $key === $this->selectedLegend,
                ARRAY_FILTER_USE_KEY
            );
        }

        // Get users who don't have any roles (students)
        $students = User::whereDoesntHave('roles')->distinct()->get(['id', 'name']);

        $years = Schedule::distinct()->orderBy('year', 'desc')->pluck('year');

        return view('livewire.student-table-result', [
            'legendCounts' => $legendCounts,
            'legendDescriptions' => $this->showDescriptions ? $this->legendDescriptions : [],
            'allLegends' => $this->legendDescriptions,
            'students' => $students,
            'years' => $years
        ]);
    }

    // Method to update the selected legend
    public function updateLegendFilter($legend)
    {
        $this->selectedLegend = $legend;
    }

    // Rename method to reflect student filter
    public function updateStudentFilter($studentId)
    {
        $this->selectedProfessor = $studentId; // Keep using same property for now
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

        // Update filter to look for schedule with related user
        if (!empty($this->selectedProfessor)) {
            $query->whereHas('schedule', function ($q) {
                $q->whereHas('users', function ($subQ) {
                    $subQ->where('users.id', $this->selectedProfessor);
                });
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

            foreach ($studentActivities as $timeInterval => $activities) {
                foreach ($activities as $activity) {
                    if (!isset($legendCounts[$activity])) {
                        $legendCounts[$activity] = 0;
                    }
                    $legendCounts[$activity]++;
                }
            }
        }

        ksort($legendCounts);
        return $legendCounts;
    }
}
