<?php
namespace App\Patterns\Decorator;

use App\Models\Order;
use Carbon\Carbon;

class MondayDiscountDecorator implements PriceCalculatorInterface
{
    public function __construct(private PriceCalculatorInterface $interface) {}

    public function calculate(Order $order): float
    {
        $price_base = $this->interface->calculate($order);
        $today = Carbon::now()->dayOfWeek === Carbon::MONDAY;

        if ($today) {
            $price_base = $price_base * 0.90;
        }
        
        return round($price_base, 2);
    }
}
