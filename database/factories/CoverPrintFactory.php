<?php

namespace Database\Factories;

use App\Models\CoverPrint;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoverPrint>
 */
class CoverPrintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject' => fake()->words(2, true),
            'subject_code' => fake()->bothify('??-###'),
            'page_type' => 'Assignment '.fake()->numberBetween(1, 5),
            'teacher' => fake()->name(),
            'second_teacher' => null,
            'submitted_on' => fake()->dateTimeBetween('-1 month', '+1 week'),
        ];
    }
}
