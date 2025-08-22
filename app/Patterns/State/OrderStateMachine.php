<?php

namespace App\Patterns\State;

use App\Models\Order;
use Illuminate\Validation\ValidationException;

class OrderStateMachine implements OrderStateInterface
{
    private array $transitions = [
        'PENDING' => ['PROCESSING', 'CANCELLED'],
        'PROCESSING' => ['SHIPPED', 'CANCELLED'],
        'SHIPPED' => ['DELIVERED', 'CANCELLED'],
        'DELIVERED' => ['CANCELLED'],
        'CANCELLED' => [],
    ];

    private function canChangeState(string $from, string $to): bool
    {
        if (!isset($this->transitions[$from])) {
            return false;
        }

        return in_array($to, $this->transitions[$from], true);
    }

    public function applyState(Order $order, string $newStatus): Order
    {
        if ($order->status === $newStatus) {
            return $order;
        }

        if ($newStatus === Order::STATUS_ORDER['cancelled']) {
            $order->update(['status' => $newStatus]);

            return $order;
        }

        if (!$this->canChangeState($order->status, $newStatus)) {
            throw ValidationException::withMessages([
                'status' => "No se puede cambiar de estado {$order->status} a {$newStatus}"
            ]);
        }

        $order->update(['status' => $newStatus]);

        return $order;
    }
}
