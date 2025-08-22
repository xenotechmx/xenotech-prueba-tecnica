<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\OrderItem;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'customer_type' => $this->faker->randomElement(['REGULAR','PREMIUM','VIP']),
            'status' => $this->faker->randomElement(['PENDING','PROCESSING','SHIPPED']),
            'total_amount' => 0,
        ];
    }

    public function regular()
    {
        return $this->state(fn() => ['customer_type' => 'REGULAR']);
    }

    public function premium()
    {
        return $this->state(fn() => ['customer_type' => 'PREMIUM']);
    }

    public function vip()
    {
        return $this->state(fn() => ['customer_type' => 'VIP']);
    }

    public function withItems(int $count = 1)
    {
        return $this->has(OrderItem::factory()->count($count), 'items');
    }
}
