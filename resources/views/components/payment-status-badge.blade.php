@props(['status'])

@php
    $config = match ($status) {
        'pending' => ['classes' => 'bg-yellow-400/10 text-yellow-400 border-yellow-400/30', 'icon' => 'fa-clock', 'label' => 'Pending'],
        'waiting_verification' => ['classes' => 'bg-blue-400/10 text-blue-400 border-blue-400/30', 'icon' => 'fa-hourglass-half', 'label' => 'Waiting Verification'],
        'paid' => ['classes' => 'bg-emerald-400/10 text-emerald-400 border-emerald-400/30', 'icon' => 'fa-circle-check', 'label' => 'Paid'],
        default => ['classes' => 'bg-stone-400/10 text-stone-400 border-stone-400/30', 'icon' => 'fa-circle', 'label' => str((string) $status)->headline()->value()],
    };
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-bold {$config['classes']}") }}>
    <i class="fa-solid {{ $config['icon'] }} text-[10px]"></i>
    {{ $config['label'] }}
</span>
