<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Patterns\Decorator\PriceCalculatorFactory;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory()
            ->count(10)
            ->create()
            ->each(function (Order $order) {
                OrderItem::factory()->count(rand(1, 3))->create([
                    'order_id' => $order->id,
                ]);

                $this->calculateAndPersistTotal($order);
            });

        Order::factory()
            ->vip()
            ->count(5)
            ->create()
            ->each(function (Order $order) {
                OrderItem::factory()->count(2)->create([
                    'order_id' => $order->id,
                ]);

                $this->calculateAndPersistTotal($order);
            });

        Order::factory()
            ->premium()
            ->count(5)
            ->create()
            ->each(function (Order $order) {
                OrderItem::factory()->count(3)->create([
                    'order_id' => $order->id,
                ]);

                $this->calculateAndPersistTotal($order);
            });
    }

    protected function calculateAndPersistTotal(Order $order): void
    { {
            $order->load('items');

            if ($order->items->isEmpty()) {
                $order->total_amount = 0;
                $order->save();
                return;
            }

            $now = Carbon::now();
            $dow = $now->dayOfWeek;
            $isMonday = $now->isMonday();

            if (
                in_array($dow, [Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY], true)
                && is_null($order->random_discount_percent)
            ) {
                $order->random_discount_percent = rand(1, 3);
                $order->save();
            }

            $randomPercent = $order->random_discount_percent;

            if (class_exists(PriceCalculatorFactory::class)) {
                $calculator = PriceCalculatorFactory::getInstance($randomPercent);
                $total = $calculator->calculate($order);
            } else {
                $subtotal = $order->items->reduce(function ($carry, $item) {
                    return $carry + ($item->price * $item->quantity);
                }, 0.0);

                $total = $subtotal;

                if ($isMonday) {
                    $total *= 0.90;
                }

                if (in_array($dow, [Carbon::MONDAY, Carbon::TUESDAY, Carbon::WEDNESDAY, Carbon::THURSDAY], true)) {
                    $percent = $randomPercent ?? rand(1, 3);
                    $total *= (1 - ($percent / 100));
                }

                $total = round($total, 2);
            }

            $order->total_amount = round($total, 2);
            $order->save();
        }
    }
}
