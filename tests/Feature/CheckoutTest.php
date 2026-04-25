<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_with_correct_total(): void
    {
        Mail::fake();

        $menu = Menu::factory()->create([
            'category_id' => Category::factory(),
            'name' => 'Test Coffee',
            'price' => 20000,
        ]);

        $response = $this->withSession([
            'cart' => [
                'items' => [
                    $menu->id => [
                        'menu_id' => $menu->id,
                        'name' => $menu->name,
                        'price' => 20000,
                        'quantity' => 2,
                        'note' => 'Less sugar',
                    ],
                ],
            ],
        ])->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_email' => 'john@example.com',
            'table_number' => '5',
            'payment_method' => 'cashier',
        ]);

        $order = Order::with('items')->first();

        $this->assertNotNull($order);
        $response->assertRedirect('/checkout/success');
        $response->assertSessionHas('checkout_order_id', $order->id);
        $response->assertSessionHas('order_code', $order->order_code);
        $this->assertEquals('40000.00', $order->total_price);
        $this->assertEquals('cashier', $order->payment_method);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertCount(1, $order->items);
        $this->assertEquals('Test Coffee', $order->items->first()->menu_name_snapshot);
        $this->assertEquals('40000.00', $order->items->first()->subtotal);

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function test_checkout_with_empty_cart_fails(): void
    {
        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_email' => 'john@example.com',
            'table_number' => '5',
            'payment_method' => 'cashier',
        ]);

        $response->assertSessionHasErrors('cart');
    }

    public function test_checkout_with_missing_fields_fails(): void
    {
        $menu = Menu::factory()->create([
            'category_id' => Category::factory(),
        ]);

        $response = $this->withSession([
            'cart' => [
                'items' => [
                    $menu->id => [
                        'menu_id' => $menu->id,
                        'name' => $menu->name,
                        'price' => (float) $menu->price,
                        'quantity' => 1,
                        'note' => null,
                    ],
                ],
            ],
        ])->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'table_number' => '5',
            'payment_method' => 'cashier',
        ]);

        $response->assertSessionHasErrors('customer_email');
    }

    public function test_checkout_clears_cart_after_success(): void
    {
        $menu = Menu::factory()->create([
            'category_id' => Category::factory(),
        ]);

        $response = $this->withSession([
            'cart' => [
                'items' => [
                    $menu->id => [
                        'menu_id' => $menu->id,
                        'name' => $menu->name,
                        'price' => (float) $menu->price,
                        'quantity' => 1,
                        'note' => null,
                    ],
                ],
            ],
        ])->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_email' => 'john@example.com',
            'table_number' => '5',
            'payment_method' => 'cashier',
        ]);

        $response->assertRedirect('/checkout/success');
        $this->assertEquals([], session('cart', []));
    }

    public function test_qris_payment_proof_upload_updates_status(): void
    {
        Storage::fake('public');

        $menu = Menu::factory()->create([
            'category_id' => Category::factory(),
            'price' => 30000,
        ]);

        $checkoutResponse = $this->withSession([
            'cart' => [
                'items' => [
                    $menu->id => [
                        'menu_id' => $menu->id,
                        'name' => $menu->name,
                        'price' => 30000,
                        'quantity' => 1,
                        'note' => null,
                    ],
                ],
            ],
        ])->post('/checkout', [
            'customer_name' => 'Jane Doe',
            'customer_phone' => '08123456780',
            'customer_email' => 'jane@example.com',
            'table_number' => '8',
            'payment_method' => 'qris',
        ]);

        $order = Order::first();

        $checkoutResponse->assertRedirect('/checkout/success');
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->payment_status);

        $uploadResponse = $this->withSession([
            'checkout_order_id' => $order->id,
        ])->post('/checkout/payment-proof', [
            'payment_proof' => UploadedFile::fake()->image('proof.png', 600, 600),
        ]);

        $uploadResponse->assertRedirect();
        $uploadResponse->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('waiting_verification', $order->payment_status);
        $this->assertNotNull($order->payment_proof);
        $this->assertTrue(Storage::disk('public')->exists($order->payment_proof));
    }
}
