<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'website' => $this->faker->url,
            'slug' => $this->faker->slug,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'region_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}
