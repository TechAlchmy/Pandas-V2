<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'auth_level' => 0,
            'social_security_number' => $this->faker->numberBetween(100000000, 999999999),
            'organization_id' => null,
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->numberBetween(10000, 99999),
            'country' => $this->faker->country(),
            'created_by_id' => null,
            'updated_by_id' => null,
            'deleted_by_id' => null,
            'remember_token' => Str::random(10),
            'organization_verified_at' => now(),
        ];
    }

    public function admins(): static
    {
        return $this->state(fn (array $attributes) => [
            'auth_level' => 1,
        ]);
    }

    public function superAdmins(): static
    {
        return $this->state(fn (array $attributes) => [
            'auth_level' => 3,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
