@props(['method'])

@php
    $config = match ($method) {
        'cashier' => ['classes' => 'bg-cyan-400/10 text-cyan-400 border-cyan-400/30', 'icon' => 'fa-cash-register', 'label' => 'Cashier'],
        'qris' => ['classes' => 'bg-violet-400/10 text-violet-400 border-violet-400/30', 'icon' => 'fa-qrcode', 'label' => 'QRIS'],
        default => ['classes' => 'bg-stone-400/10 text-stone-400 border-stone-400/30', 'icon' => 'fa-circle', 'label' => str((string) $method)->headline()->value()],
    };
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold {$config['classes']}") }}>
    <i class="fa-solid {{ $config['icon'] }} text-[10px]"></i>
    {{ $config['label'] }}
</span>
