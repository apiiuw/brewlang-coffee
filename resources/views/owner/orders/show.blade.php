@extends('layouts.owner')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in-up">
    <div class="mb-6">
        <a href="{{ route('owner.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-stone-500 hover:text-amber-400 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to orders
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_300px]">

        {{-- Order Detail --}}
        <section class="rounded-2xl border border-stone-800 bg-stone-900 p-6">
            <div class="flex flex-col gap-4 border-b border-stone-800 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-400/70">Order Detail</p>
                    <h1 class="font-display mt-2 text-3xl font-black text-stone-50">{{ $order->order_code }}</h1>
                    <p class="mt-1 text-stone-500">{{ $order->customer_name }} &bull; Table {{ $order->table_number }}</p>
                </div>
                <x-order-status-badge :status="$order->status" class="self-start" />
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500"><i class="fa-solid fa-envelope mr-1.5"></i>Email</p>
                    <p class="mt-1.5 font-semibold text-stone-200 text-sm">{{ $order->customer_email }}</p>
                </div>
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500"><i class="fa-solid fa-phone mr-1.5"></i>Phone</p>
                    <p class="mt-1.5 font-semibold text-stone-200 text-sm">{{ $order->customer_phone }}</p>
                </div>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500">Payment Method</p>
                    <div class="mt-2">
                        <x-payment-method-badge :method="$order->payment_method" />
                    </div>
                </div>
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500">Payment Status</p>
                    <div class="mt-2">
                        <x-payment-status-badge :status="$order->payment_status" />
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-xl bg-stone-800 border border-stone-700 p-4">
                <p class="text-xs text-stone-500">Payment Proof</p>
                @if($order->payment_proof_url)
                    <img src="{{ $order->payment_proof_url }}" alt="Payment proof {{ $order->order_code }}" class="mt-2 h-56 w-full rounded-xl border border-stone-700 object-contain bg-stone-900">
                @else
                    <p class="mt-2 text-sm text-stone-500">No payment proof uploaded.</p>
                @endif
            </div>

            <div class="mt-8 space-y-3 lg:hidden">
                @foreach($order->items as $item)
                    <article class="rounded-2xl border border-stone-800 bg-stone-800/50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-stone-200">{{ $item->menu_name_snapshot }}</p>
                                <p class="mt-1 text-xs text-stone-500">Qty {{ $item->quantity }} • IDR {{ number_format($item->price_snapshot, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-sm font-semibold text-stone-200">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-3 rounded-xl border border-stone-700 bg-stone-900/60 p-3">
                            <p class="text-[11px] uppercase tracking-wider text-stone-500">Note</p>
                            <p class="mt-1 text-sm text-stone-300">{{ $item->item_note ?: '-' }}</p>
                        </div>
                    </article>
                @endforeach
                <div class="rounded-2xl border border-stone-800 bg-stone-800/50 p-4 text-right">
                    <p class="text-xs uppercase tracking-wider text-stone-500">Total</p>
                    <p class="mt-2 text-lg font-black text-amber-400">IDR {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-8 hidden overflow-x-auto lg:block">
                <table class="min-w-full table-dark">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Note</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="font-semibold text-stone-200">{{ $item->menu_name_snapshot }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>IDR {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                                <td class="text-stone-600 text-xs">{{ $item->item_note ?: '-' }}</td>
                                <td class="text-right font-semibold text-stone-200">IDR {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-stone-700 bg-stone-800/50">
                            <td colspan="4" class="px-6 py-4 text-right font-bold text-stone-400">Total</td>
                            <td class="px-6 py-4 text-right font-black text-amber-400">IDR {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        {{-- Summary Sidebar --}}
        <aside class="rounded-2xl border border-stone-800 bg-stone-900 p-6 h-fit">
            <h2 class="font-semibold text-stone-200 mb-5">
                <i class="fa-solid fa-circle-info text-amber-400/60 mr-2 text-sm"></i>
                Summary
            </h2>
            <div class="space-y-3">
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500">Items</p>
                    <p class="mt-1.5 text-2xl font-black text-stone-100">{{ $order->items->sum('quantity') }}</p>
                </div>
                <div class="rounded-xl bg-stone-800 border border-stone-700 p-4">
                    <p class="text-xs text-stone-500">Created</p>
                    <p class="mt-1.5 font-semibold text-stone-200 text-sm">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
