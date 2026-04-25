<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::exists()) {
            return;
        }

        $menus = Menu::query()->orderBy('id')->get();

        if ($menus->count() < 8) {
            return;
        }

        $statuses = [
            'unpaid', 'unpaid',
            'paid', 'paid',
            'in_progress', 'in_progress',
            'all_done', 'all_done',
        ];

        foreach ($statuses as $index => $status) {
            $items = $this->buildItemsForIndex($menus, $index);
            $total = $items->sum(fn (array $item) => $item['subtotal']);

            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'customer_name' => fake()->name(),
                'customer_phone' => fake()->numerify('08##########'),
                'customer_email' => fake()->unique()->safeEmail(),
                'table_number' => (string) (($index % 12) + 1),
                'payment_method' => $index % 2 === 0 ? 'cashier' : 'qris',
                'payment_status' => $index % 2 === 0 ? 'pending' : 'waiting_verification',
                'status' => $status,
                'total_price' => $total,
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
            }
        }
    }

    private function buildItemsForIndex(Collection $menus, int $index): Collection
    {
        $itemCount = 2 + ($index % 3); // 2-4 items

        return collect(range(0, $itemCount - 1))->map(function (int $offset) use ($menus, $index) {
            $menu = $menus[($index + $offset) % $menus->count()];
            $quantity = (($index + $offset) % 2) + 1;
            $price = (float) $menu->price;

            return [
                'menu_id' => $menu->id,
                'menu_name_snapshot' => $menu->name,
                'price_snapshot' => $price,
                'quantity' => $quantity,
                'item_note' => null,
                'subtotal' => $price * $quantity,
            ];
        });
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'BRW-' . strtoupper(Str::random(6));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
