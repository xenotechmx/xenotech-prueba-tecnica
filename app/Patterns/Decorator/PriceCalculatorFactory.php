<?php

namespace App\Patterns\Decorator;

use Carbon\Carbon;

class PriceCalculatorFactory
{
    public static function getInstance(): PriceCalculatorInterface
    {
        $instance = new BasePriceCalculator();

        if (Carbon::now()->isMonday()) {
            $instance = new MondayDiscountDecorator($instance);
        }

        if (in_array(Carbon::now()->dayOfWeek, [Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY], true)) {
            $instance = new RandomAdditionalDiscountDecorator($instance);
        }

        return $instance;
    }
}
