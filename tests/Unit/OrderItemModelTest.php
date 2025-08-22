<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\OrderItem;
use InvalidArgumentException;

class OrderItemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_createOrderItem_throws_if_no_items()
    {
        $this->expectException(InvalidArgumentException::class);

        $order = Order::factory()->create();
        OrderItem::createOrderItem($order, []);
    }

    public function test_createOrderItem_creates_items_correctly()
    {
        $order = Order::factory()->create();

        $payload = [
            'items' => [
                ['product_name' => 'Item A', 'quantity' => 1, 'price' => 10.50],
                ['product_name' => 'Item B', 'quantity' => 2, 'price' => 5.00],
            ],
        ];

        OrderItem::createOrderItem($order, $payload);

        $this->assertEquals(2, $order->items()->count());
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_name' => 'Item A',
        ]);
    }
}
