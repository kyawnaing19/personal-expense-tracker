<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = \App\Models\User::first();
        $categories = \App\Models\Category::all();
        return [
            'user_id' => null,
            'category_id' => null,
            'type' => null,


            'amount' => $this->faker->numberBetween(10000, 1000000),
            'note' => $this->faker->sentence(4),
            'transaction_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}
