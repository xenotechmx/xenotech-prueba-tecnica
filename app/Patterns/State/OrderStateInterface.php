<?php
namespace App\Patterns\State;

use App\Models\Order;

interface OrderStateInterface
{
    public function applyState(Order $order, string $newStatus): Order;
}