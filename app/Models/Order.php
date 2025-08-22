<?php

namespace App\Models;

use App\Patterns\Decorator\PriceCalculatorFactory;
use App\Patterns\State\OrderStateMachine;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class Order extends Model
{
    use HasFactory;

    public const STATUS_ORDER = [
        'pending' => 'PENDING',
        'processing' => 'PROCESSING',
        'shipped' => 'SHIPPED',
        'delivered' => 'DELIVERED',
        'cancelled' => 'CANCELLED',
    ];

    protected $fillable = [
        'customer_name',
        'customer_type',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function createOrder(array $data): self|JsonResponse
    {
        $order = Order::create([
            'customer_name' => $data['customer_name'],
            'customer_type' => $data['customer_type'] ?? 'REGULAR',
            'total_amount' => 0,
            'status' => 'PENDING',
        ]);

        if (!$order) {
            return response()->json(['message' => 'Error al crear el pedido'], 500);
        }

        return $order;
    }

    public static function updateAmountOrder(Order $order, float $amount): void
    {
        if (!$order) {
            throw new InvalidArgumentException('Pedido no válido');
        }

        $order->update(['total_amount' => $amount]);
    }

    public static function updateOrder(Order $order, $data)
    {
        $stateMachine = new OrderStateMachine();
        $notifier = new NotificationService();

        if ($data['status']) {
            try {
                $stateMachine->applyState($order, $data['status']);
            } catch (ValidationException $e) {
                return response()->json(['message' => $e->errors()], 422);
            }

            $notifier->sendForOrder($order, "El pedido #{$order->id} cambió de estado a {$order->status}");
        }

        if (!empty($data['customer_name'])) {
            $order->update(['customer_name' => $data['customer_name']]);
        }

        if (!empty($data['customer_type'])) {
            $order->update(['customer_type' => $data['customer_type']]);
        }

        if (!empty($data['items'])) {
            $order->items()->delete();

            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $instance = PriceCalculatorFactory::getInstance();
            $total_amount = $instance->calculate($order);

            Order::updateAmountOrder($order, $total_amount);
        }
    }
}
