<?php
namespace App\Services;

use App\Models\Order;
use App\Patterns\Strategy\EmailNotificationStrategy;
use App\Patterns\Strategy\WhatsAppNotificationStrategy;

class NotificationService
{
    public function sendForOrder(Order $order, string $message): void
    {
        $type = $order->customer_type ?? 'REGULAR';

        switch ($type) {
            case 'PREMIUM':
                $notification = new EmailNotificationStrategy();
                break;
            case 'VIP':
                $notification = new WhatsAppNotificationStrategy();
                break;
            default:
                break;
        }

        $notification->notify($order, $message);
    }
}
