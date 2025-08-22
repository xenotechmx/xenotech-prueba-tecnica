<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

class BasePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(private float $total = 0.0) {}

    public function calculate(Order $order): float
    {
        foreach ($order->items as $item) {
            $this->total += $item->price * $item->quantity;
        }

        return round($this->total, 2);
    }
}
