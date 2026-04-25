<?php

namespace App\Http\Requests;

use App\Services\CartService;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'table_number' => ['required', 'string', 'max:20'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['required', 'email', 'max:255'],
            'payment_method' => ['required', 'in:cashier,qris'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (app(CartService::class)->isEmpty()) {
                $validator->errors()->add('cart', 'Your cart is empty.');
            }
        });
    }
}
