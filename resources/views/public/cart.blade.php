@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 border-b border-stone-800 pb-6 sm:flex-row sm:items-end sm:justify-between sm:pb-8 animate-fade-in-up">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-amber-400/70">Cart</p>
            <h1 class="font-display mt-3 text-3xl font-black tracking-tight text-stone-50 sm:text-4xl">
                Review your order.
            </h1>
        </div>
        <a href="{{ route('menu') }}" class="btn-secondary w-full justify-center sm:w-auto sm:flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Continue Browsing
        </a>
    </div>

    <div class="mt-8 grid gap-6 lg:mt-10 lg:grid-cols-[1fr_360px] animate-fade-in-up delay-100">

        {{-- Cart Items --}}
        <div class="space-y-4" id="cart-items-container">
            @forelse($cartItems as $item)
                <article class="rounded-3xl border border-stone-800 bg-stone-900 p-4 transition hover:border-stone-700 sm:p-6" data-cart-item="{{ $item['menu_id'] }}">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-stone-100">{{ $item['name'] }}</h2>
                            <p class="mt-1 text-sm text-stone-500">IDR {{ number_format($item['price'], 0, ',', '.') }} each</p>
                            <p class="mt-2 text-sm font-bold text-amber-400">
                                Subtotal:
                                <span data-item-subtotal="{{ $item['menu_id'] }}">IDR {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <button type="button"
                            class="js-remove-item btn-danger w-full justify-center flex-shrink-0 sm:w-auto"
                            data-menu-id="{{ $item['menu_id'] }}"
                            data-name="{{ $item['name'] }}">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                            Remove
                        </button>
                    </div>

                    <div class="mt-5 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        {{-- Quantity --}}
                        <div class="flex items-center justify-center gap-2 sm:justify-start">
                            <button type="button"
                                class="js-quantity-change h-11 w-11 rounded-full border border-stone-700 text-stone-300 font-bold transition hover:border-amber-400/40 hover:text-amber-400 hover:bg-amber-400/5"
                                data-menu-id="{{ $item['menu_id'] }}"
                                data-quantity="{{ $item['quantity'] - 1 }}"
                                data-name="{{ $item['name'] }}">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <div class="min-w-[4rem] rounded-xl bg-stone-800 border border-stone-700 px-4 py-2.5 text-center text-sm font-bold text-stone-200" data-item-quantity="{{ $item['menu_id'] }}">
                                {{ $item['quantity'] }}
                            </div>
                            <button type="button"
                                class="js-quantity-change h-11 w-11 rounded-full border border-stone-700 text-stone-300 font-bold transition hover:border-amber-400/40 hover:text-amber-400 hover:bg-amber-400/5"
                                data-menu-id="{{ $item['menu_id'] }}"
                                data-quantity="{{ $item['quantity'] + 1 }}"
                                data-name="{{ $item['name'] }}">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>

                        {{-- Note --}}
                        <label class="block w-full lg:max-w-sm">
                            <span class="mb-2 block text-xs font-semibold text-stone-500 uppercase tracking-wider">
                                <i class="fa-regular fa-note-sticky mr-1"></i> Item note
                            </span>
                            <textarea
                                class="js-note-input input-dark resize-none"
                                rows="2"
                                data-menu-id="{{ $item['menu_id'] }}"
                                placeholder="Less sugar, no ice, extra shot...">{{ $item['note'] }}</textarea>
                        </label>
                    </div>
                </article>
            @empty
                <x-empty-state
                    title="Your cart is empty"
                    description="Add items from the menu to start an order."
                    action-label="Browse Menu"
                    :action-href="route('menu')"
                />
            @endforelse
        </div>

        {{-- Summary Sidebar --}}
        <aside class="rounded-3xl border border-stone-800 bg-stone-900/80 p-4 dark-glass h-fit sm:p-6 lg:sticky lg:top-24">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-400/70 mb-6">
                <i class="fa-solid fa-receipt mr-1.5"></i>
                Order Summary
            </p>
            <div class="space-y-3 border-b border-stone-800 pb-6 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-stone-500">Items</span>
                    <span class="text-stone-300" id="summary-item-count">{{ $cartItems->sum('quantity') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-stone-500">Service</span>
                    <span class="text-emerald-400 font-semibold">Free</span>
                </div>
                <div class="flex items-end justify-between pt-4 border-t border-stone-800">
                    <span class="text-base font-semibold text-stone-300">Total</span>
                    <span class="text-2xl font-black text-stone-50" id="summary-total">IDR {{ number_format($cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($cartItems->isEmpty())
                <button type="button" disabled class="w-full rounded-2xl bg-stone-800 border border-stone-700 px-5 py-4 font-bold text-stone-600 cursor-not-allowed">
                    <i class="fa-solid fa-ban mr-2"></i>
                    Cart is empty
                </button>
            @else
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="customer_name" class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Customer name</label>
                        <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}"
                            class="input-dark" placeholder="Your name" required>
                        @error('customer_name')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="customer_phone" class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Phone number</label>
                        <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}"
                            class="input-dark" placeholder="08xx-xxxx-xxxx" required>
                        @error('customer_phone')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="customer_email" class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Email address</label>
                        <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}"
                            class="input-dark" placeholder="you@email.com" required>
                        @error('customer_email')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="table_number" class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Table number</label>
                        <input id="table_number" name="table_number" type="text" value="{{ old('table_number') }}"
                            class="input-dark" placeholder="e.g. 12" required>
                        @error('table_number')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <p class="mb-2 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Payment method</p>
                        <div class="grid gap-2">

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cashier"
                                    class="peer hidden"
                                    {{ old('payment_method', 'cashier') === 'cashier' ? 'checked' : '' }}>

                                <div class="flex items-start gap-3 rounded-xl border border-stone-700 bg-stone-800/60 px-3 py-3
                                    peer-checked:border-amber-400 peer-checked:bg-amber-400/10">

                                    <span class="block text-sm font-semibold text-stone-200 peer-checked:text-amber-400">
                                        Cashier
                                    </span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris"
                                    class="peer hidden"
                                    {{ old('payment_method') === 'qris' ? 'checked' : '' }}>

                                <div class="flex items-start gap-3 rounded-xl border border-stone-700 bg-stone-800/60 px-3 py-3
                                    peer-checked:border-amber-400 peer-checked:bg-amber-400/10">

                                    <span class="block text-sm font-semibold text-stone-200 peer-checked:text-amber-400">
                                        QRIS
                                    </span>
                                </div>
                            </label>

                        </div>
                        @error('payment_method')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    @error('cart')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                    <button type="submit" class="btn-primary glow-amber min-h-12 w-full mt-2 !rounded-2xl">
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                        Place Order
                    </button>
                </form>
            @endif
        </aside>
    </div>
</div>

{{-- Confirm Remove Modal --}}
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-stone-950/80 backdrop-blur-sm" id="modal-backdrop"></div>
    <div class="relative w-full max-w-sm rounded-3xl border border-stone-700 bg-stone-900 p-7 shadow-2xl animate-scale-in">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-red-400/10 border border-red-400/20">
            <i class="fa-solid fa-trash-can text-red-400"></i>
        </div>
        <h3 class="text-lg font-bold text-stone-100" id="modal-title">Remove item?</h3>
        <p class="mt-2 text-sm text-stone-500" id="modal-body">This item will be removed from your cart.</p>
        <div class="mt-6 flex gap-3">
            <button id="modal-cancel" class="btn-secondary flex-1 !rounded-2xl !py-2.5 !text-sm">
                Cancel
            </button>
            <button id="modal-confirm" class="btn-danger flex-1 !rounded-2xl !py-2.5 !text-sm !justify-center">
                <i class="fa-solid fa-trash-can text-xs"></i>
                Remove
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ── Modal helpers ──────────────────────────────────────────
    const modal      = document.getElementById('confirm-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody  = document.getElementById('modal-body');
    const modalConfirm = document.getElementById('modal-confirm');
    const modalCancel  = document.getElementById('modal-cancel');
    const modalBackdrop = document.getElementById('modal-backdrop');

    let confirmCallback = null;

    function openModal(title, body, onConfirm) {
        modalTitle.textContent = title;
        modalBody.textContent  = body;
        confirmCallback        = onConfirm;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        confirmCallback = null;
    }

    modalCancel.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);

    modalConfirm.addEventListener('click', () => {
        if (confirmCallback) {
            confirmCallback();
        }
        closeModal();
    });

    // ── HTTP helper ────────────────────────────────────────────
    const send = async (url, method, body = null, options = {}) => {
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: body ? JSON.stringify(body) : null,
        });

        if (!response.ok) {
            return null;
        }

        const payload = await response.json();

        if (options.reload !== false) {
            window.location.reload();
        }

        return payload;
    };

    const formatCurrency = (value) => `IDR ${Number(value || 0).toLocaleString('id-ID')}`;

    const updateCartBadge = (count) => {
        const cartLink = document.querySelector('a[href="{{ route('cart.index') }}"][aria-label="Cart"]');
        if (!cartLink) {
            return;
        }

        let badge = document.getElementById('cart-badge');

        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.id = 'cart-badge';
                badge.className = 'absolute -right-0.5 -top-0.5 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-amber-400 px-1 text-xs font-bold text-stone-950';
                cartLink.appendChild(badge);
            }

            badge.textContent = count;
            badge.classList.add('animate-cart-bounce');
            badge.addEventListener('animationend', () => badge.classList.remove('animate-cart-bounce'), { once: true });
            return;
        }

        badge?.remove();
    };

    const updateSummary = (payload) => {
        const itemCount = document.getElementById('summary-item-count');
        const total = document.getElementById('summary-total');

        if (itemCount) {
            itemCount.textContent = payload.count;
        }

        if (total) {
            total.textContent = formatCurrency(payload.total);
        }

        updateCartBadge(payload.count);
    };

    const updateQuantityUI = (menuId, quantity, subtotal) => {
        const article = document.querySelector(`[data-cart-item="${menuId}"]`);
        if (!article) {
            return;
        }

        const quantityLabel = article.querySelector(`[data-item-quantity="${menuId}"]`);
        const subtotalLabel = article.querySelector(`[data-item-subtotal="${menuId}"]`);
        const buttons = article.querySelectorAll('.js-quantity-change');

        if (quantityLabel) {
            quantityLabel.textContent = quantity;
        }

        if (subtotalLabel) {
            subtotalLabel.textContent = formatCurrency(subtotal);
        }

        buttons.forEach((button) => {
            const isMinus = button.querySelector('.fa-minus');
            button.dataset.quantity = String(isMinus ? quantity - 1 : quantity + 1);
        });
    };

    // ── Quantity Change ────────────────────────────────────────
    document.querySelectorAll('.js-quantity-change').forEach((button) => {
        button.addEventListener('click', () => {
            const newQty  = Number(button.dataset.quantity);
            const menuId  = button.dataset.menuId;
            const name    = button.dataset.name;

            if (newQty <= 0) {
                // Show confirmation before removing
                openModal(
                    `Remove "${name}"?`,
                    'Reducing quantity to 0 will remove this item from your cart.',
                    () => send(`/cart/remove/${menuId}`, 'DELETE'),
                );
                return;
            }

            send('{{ route('cart.updateQuantity') }}', 'POST', {
                menu_id: menuId,
                quantity: newQty,
            }, { reload: false }).then((payload) => {
                if (!payload) {
                    return;
                }

                const item = payload.cart?.items?.[menuId];
                if (!item) {
                    window.location.reload();
                    return;
                }

                updateQuantityUI(menuId, Number(item.quantity), Number(item.price) * Number(item.quantity));
                updateSummary(payload);
            });
        });
    });

    // ── Remove Button ──────────────────────────────────────────
    document.querySelectorAll('.js-remove-item').forEach((button) => {
        button.addEventListener('click', () => {
            const name   = button.dataset.name;
            const menuId = button.dataset.menuId;

            openModal(
                `Remove "${name}"?`,
                'This item will be permanently removed from your cart.',
                () => send(`/cart/remove/${menuId}`, 'DELETE'),
            );
        });
    });

    // ── Note Update (debounced) ────────────────────────────────
    let debounce;
    document.querySelectorAll('.js-note-input').forEach((input) => {
        input.addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(() => {
                send('{{ route('cart.updateNote') }}', 'POST', {
                    menu_id: input.dataset.menuId,
                    note: input.value,
                }, { reload: false });
            }, 600);
        });
    });
});
</script>
@endsection
