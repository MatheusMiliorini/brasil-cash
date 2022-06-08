<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(100, 100000),
            'payment_method' => 'credit_card',
            'async' => $this->faker->boolean(),
            'capture' => $this->faker->boolean(),
            'installments' => $this->faker->numberBetween(1, 12),
        ];
    }
}
