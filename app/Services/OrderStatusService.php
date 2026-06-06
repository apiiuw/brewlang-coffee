<?php

namespace App\Services;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderStatusService
{
    public function transition(Order $order, string $newStatus): void
    {
        if (!$order->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Cannot transition from [{$order->status}] to [{$newStatus}]."
            );
        }

        $updates = ['status' => $newStatus];

        if ($newStatus === 'paid') {
            $updates['payment_status'] = 'paid';
        }

        $order->update($updates);
        try {
            Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));
        } catch (\Throwable $exception) {
            Log::error('Status email failed', [
                'order' => $order->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
