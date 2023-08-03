<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //TODO: These should never be saved into databse.
        $orderSubtotal = $this->faker->randomFloat(2, 100, 1000);
        $orderDiscount = $orderSubtotal * $this->faker->randomFloat(2, 0, 0.3);
        $orderTax = $orderSubtotal * 0.25;
        $orderTotal = $orderSubtotal - $orderDiscount + $orderTax;

        return [
            'user_id' => null,
            'order_status' => $this->faker->randomElement(OrderStatus::values()),
            'order_number' => $this->faker->numberBetween(100000, 999999),
            'order_total' => $orderTotal,
            'order_subtotal' => $orderSubtotal,
            'order_discount' => $orderDiscount,
            'order_tax' => $orderTax,
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $this->faker->randomElement(PaymentStatus::values()),
            'created_by' => $this->faker->numberBetween(1, 20),
            'updated_by' => $this->faker->numberBetween(1, 20),
            'deleted_by' => null,
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'),

        ];
    }
}
