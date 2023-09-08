<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Discount Up to 99%', 'Off By 50%', 'Garage Sale 35%', 'Total Cashback $250']),
            'voucher_type_id' => null,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->text(),
            'is_active' => true,
            'starts_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'ends_at' => $this->faker->dateTimeBetween('now', '+1 years'),
            'api_link' => $this->faker->url,
            'link' => $this->faker->url,
            'cta' => $this->faker->numberBetween(0, 3),
            'views' => $this->faker->numberBetween(1, 100),
            'clicks' => $this->faker->numberBetween(1, 100),
            'code' => $this->faker->word,
            'amount' => [$this->faker->numberBetween(1, 100) * 100],
            'limit_qty' => $this->faker->numberBetween(1, 100),
            'limit_amount' => $this->faker->numberBetween(1, 100) * 100,
            'public_percentage' => $this->faker->numberBetween(1, 100),
            'percentage' => $this->faker->numberBetween(1, 100),
            'created_by_id' => $this->faker->numberBetween(1, 20),
            'updated_by_id' => $this->faker->numberBetween(1, 20),
            'deleted_by_id' => null,
            'brand_id' => fake()->numberBetween(1, 5),
        ];
    }
}
