<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfferType>
 */
class OfferTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->name,
            'description' => $this->faker->text,
            'created_by' => $this->faker->numberBetween(1, 20),
            'updated_by' => $this->faker->numberBetween(1, 20),
            'deleted_by' => null,
        ];
    }
}
