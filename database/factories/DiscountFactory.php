<?php

namespace Database\Factories;

use App\Enums\DiscountVoucherTypeEnum;
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
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->text(),
            'is_active' => true,
            'starts_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'ends_at' => $this->faker->dateTimeBetween('now', '+1 years'),
            'api_link' => $this->faker->url,
            'link' => $this->faker->url,
            'voucher_type' => $voucherType = DiscountVoucherTypeEnum::tryFrom($this->faker->randomElement([0, 2])),
            'cta_text' => $voucherType->getDefaultLabel(),
            'views' => $this->faker->numberBetween(1, 100),
            'clicks' => $this->faker->numberBetween(1, 100),
            'code' => $this->faker->word,
            'amount' => [$this->faker->numberBetween(1, 100) * 100],
            'limit_qty' => $this->faker->numberBetween(1, 100),
            'limit_amount' => $this->faker->numberBetween(1, 100) * 100,
            'public_percentage' => $this->faker->numberBetween(1, 100) * 100,
            'percentage' => $this->faker->numberBetween(1, 100) * 100,
            'created_by_id' => $this->faker->numberBetween(1, 20),
            'updated_by_id' => $this->faker->numberBetween(1, 20),
            'deleted_by_id' => null,
            'brand_id' => fake()->numberBetween(1, 5),
        ];
    }
}
