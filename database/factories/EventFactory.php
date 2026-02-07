<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $capacity = fake()->numberBetween(10, 200);
        
        return [
            'title' => fake()->sentence(3),
            'starts_at' => fake()->dateTimeBetween('now', '+6 months'),
            'capacity' => $capacity,
            'seats_taken' => fake()->numberBetween(0, $capacity),
        ];
    }

    public function full(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'seats_taken' => $attributes['capacity'],
            ];
        });
    }

    public function available(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'seats_taken' => 0,
            ];
        });
    }
}
