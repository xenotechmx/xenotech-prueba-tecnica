<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderItem;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'product_name' => $this->faker->words(mt_rand(1,3), true),
            'quantity' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->randomFloat(2, 1, 200),
        ];
    }
}
