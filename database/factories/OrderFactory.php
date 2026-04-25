<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_code' => 'BRW-' . strtoupper(Str::random(6)),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'table_number' => (string) fake()->numberBetween(1, 20),
            'payment_method' => 'cashier',
            'payment_status' => 'pending',
            'payment_proof' => null,
            'status' => 'unpaid',
            'total_price' => fake()->numberBetween(20000, 200000),
        ];
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unpaid',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    public function allDone(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'all_done',
        ]);
    }
}
