<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_orders()
    {
        Order::factory()->count(12)
            ->has(OrderItem::factory()->count(2), 'items')
            ->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertArrayHasKey('data', $json);
        $this->assertCount(10, $json['data']);
        $this->assertEquals(12, $json['total']);
    }
}
