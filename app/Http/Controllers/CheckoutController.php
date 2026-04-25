<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CheckoutService $checkoutService,
    ) {
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cartService;
        $order = $this->checkoutService->checkout(
            $request->validated(),
            $cart->getCart()['items']
        );

        $cart->clearCart();

        return redirect()
            ->route('checkout.success')
            ->with('checkout_order_id', $order->id)
            ->with('order_code', $order->order_code);
    }

    public function success(): View|RedirectResponse
    {
        $orderId = session('checkout_order_id');

        if (!$orderId) {
            return redirect()->route('home');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home');
        }

        return view('public.checkout-success', [
            'order' => $order,
            'orderCode' => $order->order_code,
            'qrisImageUrl' => sprintf(
                'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=%s',
                urlencode('BREWLANG|' . $order->order_code)
            ),
        ]);
    }

    public function uploadPaymentProof(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        $orderId = $request->integer('order_id') ?: session('checkout_order_id');

        if (!$orderId) {
            return redirect()->route('home');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home');
        }

        if ($order->payment_method !== 'qris') {
            return back()->with('error', 'Payment proof is only required for QRIS orders.');
        }

        $validated = $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $validated['payment_proof']->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'waiting_verification',
        ]);

        return back()->with('success', 'Payment proof uploaded. Waiting for verification.');
    }
}
