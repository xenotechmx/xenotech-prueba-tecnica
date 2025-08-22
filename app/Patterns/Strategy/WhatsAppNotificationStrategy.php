<?php
namespace App\Patterns\Strategy;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationStrategy implements NotificationStrategyInterface
{
    public function notify(Order $order, string $message): void
    {
        $webhook = config('services.webhook.url');

        if (!$webhook) return;
        
        Http::post($webhook, [
            'type' => 'whatsapp',
            'to' => $order->customer_name,
            'order_id' => $order->id,
            'message' => $message,
        ]);
    }
}
