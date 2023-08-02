<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountType>
 */
class DiscountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'offertype_id' => $this->faker->numberBetween(1, 10),
            'discount_id' => $this->faker->numberBetween(1, 10),
            'created_by' => $this->faker->numberBetween(1, 20),
            'updated_by' => $this->faker->numberBetween(1, 20),
            'deleted_by' => null,
        ];
    }
}
