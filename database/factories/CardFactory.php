<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'card_number' => $this->faker->creditCardNumber(),
            'card_expiration_date' => $this->faker->creditCardExpirationDate(),
            'card_holder_name' => $this->faker->name(),
            'card_cvv' => $this->faker->numberBetween(0, 999),
        ];
    }
}
