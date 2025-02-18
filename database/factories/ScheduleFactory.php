<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => \App\Models\Course::factory(),
            'professor_id' => \App\Models\User::factory(),
            'subject_id' => \App\Models\Subject::factory(),
            'room_id' => \App\Models\Room::factory(),
            'name' => $this->faker->word,
            'time' => $this->faker->time,
            'semester' => $this->faker->randomElement(['1st Semester', '2nd Semester', 'Summer']),
            'year' => $this->faker->year,
            'is_active' => $this->faker->boolean,
        ];
    }
}
