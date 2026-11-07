<?php

namespace Database\Factories;

use App\Enums\TicketCategory;
use App\Enums\TicketStatus;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicket>
 */
class SupportTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'category' => TicketCategory::Payment,
            'transaction_id' => fake()->bothify('T##########'),
            'message' => fake()->paragraph(),
            'screenshot_path' => null,
            'status' => TicketStatus::Open,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (): array => [
            'status' => TicketStatus::Resolved,
            'resolved_at' => now(),
        ]);
    }
}
