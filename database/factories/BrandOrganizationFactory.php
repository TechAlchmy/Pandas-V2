<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrandOrganization>
 */
class BrandOrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'region_id' => $this->faker->numberBetween(1, 10),
            'brand_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
