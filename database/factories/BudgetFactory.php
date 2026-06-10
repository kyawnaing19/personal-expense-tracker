<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'user_id'          => null,
            'category_id'      => null,
            'amount'           => $this->faker->numberBetween(50000, 5000000),
            'month'            => 5,
            'year'             => 2026,
            'alert_percentage' => $this->faker->randomElement([70, 80, 90]),
        ];
    }
}
