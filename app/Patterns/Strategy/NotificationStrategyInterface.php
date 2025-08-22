<?php
namespace App\Patterns\Strategy;

use App\Models\Order;

interface NotificationStrategyInterface
{
    public function notify(Order $order, string $message): void;
}
