<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'email_notification' => $this->faker->boolean(),
            'sms_notification' => $this->faker->boolean(),
            'push_notification' => $this->faker->boolean(),
            'email_marketing' => $this->faker->boolean(),
        ];
    }
}
