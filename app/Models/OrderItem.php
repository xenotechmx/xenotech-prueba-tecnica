<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function createOrderItem(Order $order, array $data): void
    {
        if(!isset($data['items']) || !is_array($data['items'])) {
            throw new InvalidArgumentException('Se debe proporcionar productos.');
        }

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }
    }
}
