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
        $orderSubtotal = intval($this->faker->randomFloat(4, 100, 10000));
        $orderDiscount = intval($orderSubtotal * $this->faker->randomFloat(2, 0, 0.3));
        $orderTax = intval($orderSubtotal * 0.25);
        $orderTotal = intval($orderSubtotal - $orderDiscount + $orderTax);

        return [
            'user_id' => $this->faker->numberBetween(1, 50),
            'order_status' => $this->faker->randomElement(OrderStatus::values()),
            'order_total' => $orderTotal,
            'order_subtotal' => $orderSubtotal,
            'order_discount' => $orderDiscount,
            'order_tax' => $orderTax,
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $this->faker->randomElement(PaymentStatus::values()),
            'created_by_id' => $this->faker->numberBetween(1, 20),
            'updated_by_id' => $this->faker->numberBetween(1, 20),
            'deleted_by_id' => null,
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'),

        ];
    }
}
