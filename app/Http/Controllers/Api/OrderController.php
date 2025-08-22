<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPostRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Patterns\Decorator\PriceCalculatorFactory;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with('items')->paginate(10);

            return response()->json($orders);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with('items')->find($id);

            if (!$order) {
                return response()->json(['message' => 'Pedido no encontrado'], 404);
            }

            return response()->json($order);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(OrderPostRequest $request)
    {
        try {
            $order = Order::createOrder($request->validated());

            OrderItem::createOrderItem($order, $request['items']);

            $instance = PriceCalculatorFactory::getInstance();
            $total_amount = $instance->calculate($order);

            Order::updateAmountOrder($order, $total_amount);

            return response()->json($order->load('items'), 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(OrderUpdateRequest $request, $id)
    {
        try {
            $order = Order::with('items')->findOrFail($id);

            Order::updateOrder($order, $request->validated());

            return response()->json($order->load('items'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
