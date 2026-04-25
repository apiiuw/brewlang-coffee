<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StaffOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_update_order_status_forward(): void
    {
        Mail::fake();

        $staff = User::factory()->staff()->create();
        $order = Order::factory()->unpaid()->create([
            'customer_email' => 'customer@example.com',
        ]);

        $response = $this->actingAs($staff)->patch("/staff/orders/{$order->id}/status", [
            'status' => 'paid',
        ]);

        $response->assertRedirect();
        $this->assertEquals('paid', $order->fresh()->status);
        Mail::assertSent(\App\Mail\OrderStatusUpdatedMail::class, function (\App\Mail\OrderStatusUpdatedMail $mail) {
            return $mail->hasTo('customer@example.com');
        });
    }

    public function test_staff_cannot_regress_order_status(): void
    {
        $staff = User::factory()->staff()->create();
        $order = Order::factory()->allDone()->create();

        $response = $this->actingAs($staff)->patch("/staff/orders/{$order->id}/status", [
            'status' => 'unpaid',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals('all_done', $order->fresh()->status);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $staff = User::factory()->staff()->create();
        $order = Order::factory()->paid()->create();

        $response = $this->actingAs($staff)->patch("/staff/orders/{$order->id}/status", [
            'status' => 'all_done',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('paid', $order->fresh()->status);
    }
}
