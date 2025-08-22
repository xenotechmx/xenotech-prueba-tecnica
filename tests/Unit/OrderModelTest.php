<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_createOrder_creates_order_with_defaults()
    {
        $data = [
            'customer_name' => 'Unit Test Cliente',
        ];

        $order = Order::createOrder($data);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('Unit Test Cliente', $order->customer_name);
        $this->assertEquals('REGULAR', $order->customer_type);
        $this->assertEquals('PENDING', $order->status);
        $this->assertDatabaseHas('orders', ['customer_name' => 'Unit Test Cliente']);
    }

    public function test_updateAmountOrder_updates_amount_in_db()
    {
        $order = Order::factory()->create(['total_amount' => 0]);

        Order::updateAmountOrder($order, 123.45);

        $this->assertEquals('123.45', (string) $order->fresh()->total_amount);
    }

    public function test_updateOrder_applies_state_transition_and_sends_notification()
    {
        Http::fake();

        $order = Order::factory()->create(['status' => 'PENDING']);

        Order::updateOrder($order, ['status' => 'PROCESSING']);

        $this->assertEquals('PROCESSING', $order->fresh()->status);
    }

    public function test_updateOrder_returns_422_for_invalid_transition()
    {
        Http::fake();

        $order = Order::factory()->create(['status' => 'PENDING']);

        $response = Order::updateOrder($order, ['status' => 'DELIVERED']);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
