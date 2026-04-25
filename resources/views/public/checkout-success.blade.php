@extends('layouts.app')

@section('content')
<div class="flex min-h-[85vh] items-center justify-center px-4 py-10 sm:py-16">
    <div class="w-full max-w-lg animate-scale-in">
        <div class="rounded-3xl border border-stone-800 bg-stone-900/80 dark-glass p-5 text-center sm:p-10">

            {{-- Success Icon --}}
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full border border-amber-400/20 bg-amber-400/10 glow-amber animate-glow-pulse sm:h-20 sm:w-20">
                <i class="fa-solid fa-circle-check text-2xl text-amber-400 sm:text-3xl"></i>
            </div>

            <p class="text-xs font-bold uppercase tracking-[0.3em] text-amber-400/70">Checkout Complete</p>
            <h1 class="font-display mt-3 text-2xl font-black tracking-tight text-stone-50 sm:text-3xl">Your order is placed!</h1>
            @if(session('success'))
                <div class="alert-success-dark mt-4 flex items-center gap-3 text-left">
                    <i class="fa-solid fa-circle-check text-emerald-400 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error-dark mt-4 flex items-center gap-3 text-left">
                    <i class="fa-solid fa-circle-exclamation text-red-400 shrink-0"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($order->payment_method === 'cashier')
                <p class="mt-4 text-stone-500 leading-7">
                    Silakan lanjut ke kasir untuk menyelesaikan pembayaran.
                </p>
            @else
                <p class="mt-4 text-stone-500 leading-7">
                    Scan QRIS di bawah ini, lalu upload bukti pembayaran Anda.
                </p>
            @endif

            {{-- Order Code --}}
            <div class="mt-8 rounded-2xl border border-amber-400/20 bg-amber-400/5 p-4 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-stone-500">Order Code</p>
                <p class="mt-3 break-all text-3xl font-black tracking-[0.1em] text-gradient-amber sm:text-4xl sm:tracking-[0.15em]">{{ $orderCode }}</p>
            </div>

            <div class="mt-4 grid gap-3 text-left sm:grid-cols-2">
                <div class="rounded-2xl border border-stone-800 bg-stone-900 p-3">
                    <p class="text-[11px] uppercase tracking-wider text-stone-500">Payment Method</p>
                    <p class="mt-1 text-sm font-semibold text-stone-200">{{ $order->payment_method_label }}</p>
                </div>
                <div class="rounded-2xl border border-stone-800 bg-stone-900 p-3">
                    <p class="text-[11px] uppercase tracking-wider text-stone-500">Payment Status</p>
                    <p class="mt-1 text-sm font-semibold text-stone-200">{{ $order->payment_status_label }}</p>
                </div>
            </div>

            @if($order->payment_method === 'qris')
                <div class="mt-6 rounded-2xl border border-stone-800 bg-stone-900 p-4 text-left">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-400/70">QRIS</p>
                    <img src="{{ $qrisImageUrl }}" alt="QRIS code" class="mx-auto mt-3 h-56 w-56 rounded-2xl border border-stone-700 bg-white p-2 object-contain">

                    <form action="{{ route('checkout.paymentProof') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div>
                            <label for="payment_proof" class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Upload bukti pembayaran</label>
                            <input id="payment_proof" type="file" name="payment_proof" accept="image/jpeg,image/png" class="block w-full text-sm text-stone-400 file:mr-3 file:rounded-xl file:border file:border-amber-400/20 file:bg-amber-400/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-amber-400">
                            <p class="mt-1 text-xs text-stone-600">Format: JPG/PNG, maksimal 2MB.</p>
                            @error('payment_proof')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center !rounded-xl">
                            <i class="fa-solid fa-upload text-xs"></i>
                            Upload Bukti Pembayaran
                        </button>
                    </form>

                    @if($order->payment_proof_url)
                        <div class="mt-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Bukti terbaru</p>
                            <img src="{{ $order->payment_proof_url }}" alt="Payment proof" class="mt-2 h-40 w-full rounded-xl border border-stone-700 object-cover">
                        </div>
                    @endif
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap sm:justify-center">
                <a href="{{ route('menu') }}" class="btn-primary justify-center">
                    <i class="fa-solid fa-utensils text-sm"></i>
                    Back to Menu
                </a>
                <a href="{{ route('home') }}" class="btn-secondary justify-center">
                    <i class="fa-solid fa-house text-sm"></i>
                    Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
