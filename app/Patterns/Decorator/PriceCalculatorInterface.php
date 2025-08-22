<?php
namespace App\Patterns\Decorator;

use App\Models\Order;

interface PriceCalculatorInterface
{
    public function calculate(Order $order): float;
}