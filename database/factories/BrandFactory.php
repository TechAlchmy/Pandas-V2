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
            'name' => $this->faker->randomElement(['Adidas', 'Nike', 'New Balance', 'Reebok']),
            'link' => $this->faker->url,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text,
            'logo' => $this->faker->imageUrl(),
            'views' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
            'created_by_id' => $this->faker->numberBetween(1, 20),
            'updated_by_id' => $this->faker->numberBetween(1, 20),
            'deleted_by_id' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($record) {
            try {
                $record->addMedia(public_path('storage/logo/' . match (strtolower($record->name)) {
                    'adidas' => 'adidas-white.png',
                    'boss' => 'boss.png',
                    'new balance' => 'nb.png',
                    'nike' => 'nike_white.png',
                    'puma' => 'puma.png',
                    'reebok' => 'reebok.png',
                    'sketchers' => 'sketchers.png',
                    default => 'adidas-white.png',
                }))
                    ->preservingOriginal()
                    ->toMediaCollection('logo');
            } catch (\Throwable $e) {
                //
            }
        });
    }
}
