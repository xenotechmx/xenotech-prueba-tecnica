<?php

namespace App\Patterns\Decorator;

use App\Models\Order;
use Carbon\Carbon;

class RandomAdditionalDiscountDecorator implements PriceCalculatorInterface
{
    public function __construct(private PriceCalculatorInterface $interface) {}

    public function calculate(Order $order): float
    {
        $amount = $this->interface->calculate($order);
        $today = Carbon::now()->dayOfWeek;

        if (in_array($today, [Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY], true)) {
            $percent = rand(1, 3);
            $amount = $amount * (1 - ($percent / 100));
        }
        
        return round($amount, 2);
    }
}
