<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutService
{
    public function checkout(array $customerData, array $cartItems): Order
    {
        if (empty($cartItems)) {
            throw new Exception('Cannot process an empty cart.');
        }

        return DB::transaction(function () use ($customerData, $cartItems) {
            $menuIds = collect($cartItems)->pluck('menu_id')->all();
            $menus = Menu::whereIn('id', $menuIds)->lockForUpdate()->get()->keyBy('id');
            $total = collect($cartItems)->sum(fn (array $item) => ((float) $item['price']) * ((int) $item['quantity']));

            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'customer_name' => $customerData['customer_name'],
                'customer_phone' => $customerData['customer_phone'],
                'customer_email' => $customerData['customer_email'],
                'table_number' => $customerData['table_number'],
                'payment_method' => $customerData['payment_method'],
                'payment_status' => 'pending',
                'payment_proof' => null,
                'status' => 'unpaid',
                'total_price' => $total,
            ]);

            foreach ($cartItems as $item) {
                $menu = $menus->get($item['menu_id']);

                if (!$menu || !$menu->is_active) {
                    throw new Exception("Menu item '{$item['name']}' is no longer available.");
                }

                $quantity = (int) $item['quantity'];
                $priceSnapshot = (float) $item['price'];

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'menu_name_snapshot' => $item['name'],
                    'price_snapshot' => $priceSnapshot,
                    'quantity' => $quantity,
                    'item_note' => $item['note'] ?? null,
                    'subtotal' => round($priceSnapshot * $quantity, 2),
                ]);
            }
            $order->load('items');

            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Throwable $exception) {
                Log::error('Checkout email failed', [
                    'order' => $order->id,
                    'error' => $exception->getMessage(),
                ]);
            }

            return $order;
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
