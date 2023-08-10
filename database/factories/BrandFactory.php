<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'link' => $this->faker->url,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text,
            'logo' => $this->faker->imageUrl(),
            'views' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->numberBetween(0, 1),
            'created_by_id' => $this->faker->numberBetween(1, 20),
            'updated_by_id' => $this->faker->numberBetween(1, 20),
            'deleted_by_id' => null,
        ];
    }
}
