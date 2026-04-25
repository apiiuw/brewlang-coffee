@extends('layouts.owner')

@section('content')
<style>
@media print {
    aside { display: none !important; }
    .no-print { display: none !important; }
    body { background-color: #fff !important; color: #000 !important; }
    main { padding: 0 !important; }
    .rounded-2xl,
    .bg-stone-900,
    .bg-amber-400\/5,
    .border-stone-800,
    .border-amber-400\/20 {
        background: #fff !important;
        border-color: #d6d3d1 !important;
        box-shadow: none !important;
    }
    .max-h-\[500px\],
    .overflow-y-auto,
    .overflow-hidden {
        max-height: none !important;
        overflow: visible !important;
    }
    table,
    thead,
    tbody,
    tr,
    th,
    td,
    p,
    h1,
    h2,
    h3,
    span,
    i {
        color: #000 !important;
    }
    .text-amber-400,
    .text-emerald-400,
    .text-red-400,
    .text-stone-50,
    .text-stone-200,
    .text-stone-500,
    .text-stone-600,
    .text-gradient-amber {
        color: #000 !important;
        -webkit-text-fill-color: #000 !important;
    }
    .table-dark th,
    .table-dark td {
        border-color: #d6d3d1 !important;
    }
    .print\:block {
        display: block !important;
    }
    .glow-amber {
        box-shadow: none !important;
    }
}
</style>

<div class="max-w-7xl mx-auto animate-fade-in-up">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-400/70">Finance</p>
            <h1 class="font-display mt-2 text-3xl font-black text-stone-50">Business Report</h1>
        </div>
        <a
            href="{{ route('owner.reports.pdf', ['date_from' => $date_from, 'date_to' => $date_to]) }}"
            class="no-print btn-secondary !rounded-xl flex items-center gap-2"
        >
            <i class="fa-solid fa-print text-sm"></i>
            Print Report
        </a>
    </div>

    {{-- Filter --}}
    <div class="no-print mb-8 rounded-2xl border border-stone-800 bg-stone-900 p-5">
        <form action="{{ route('owner.reports.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
            <div class="w-full sm:w-auto sm:min-w-[11rem]">
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">From Date</label>
                <input type="date" name="date_from" value="{{ $date_from }}" class="input-dark w-full">
            </div>
            <div class="w-full sm:w-auto sm:min-w-[11rem]">
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">To Date</label>
                <input type="date" name="date_to" value="{{ $date_to }}" class="input-dark w-full">
            </div>
            <button type="submit" class="btn-secondary flex w-full items-center justify-center gap-2 !rounded-xl sm:w-auto">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                Generate
            </button>
        </form>
    </div>

    {{-- Print Header (only in print) --}}
    <div class="hidden print:block mb-8 text-center border-b pb-4">
        <h2 class="text-2xl font-bold">Brewlang Financial Report</h2>
        <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($date_from)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($date_to)->format('M d, Y') }}</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-stone-800 bg-stone-900 p-5">
            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Income (Paid)</p>
                <div class="w-9 h-9 rounded-xl bg-emerald-400/10 border border-emerald-400/20 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-400 text-sm"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-black text-emerald-400">Rp {{ number_format($total_income, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-stone-800 bg-stone-900 p-5">
            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Expenses</p>
                <div class="w-9 h-9 rounded-xl bg-red-400/10 border border-red-400/20 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-down text-red-400 text-sm"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-black text-red-400">Rp {{ number_format($total_expenses, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-amber-400/20 bg-amber-400/5 p-5 glow-amber">
            <div class="flex items-start justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-400/60">Net Result</p>
                <div class="w-9 h-9 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center">
                    <i class="fa-solid fa-sack-dollar text-amber-400 text-sm"></i>
                </div>
            </div>
            <p class="mt-3 text-2xl font-black text-gradient-amber">Rp {{ number_format($total_income - $total_expenses, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Log Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Income --}}
        <div class="rounded-2xl border border-stone-800 bg-stone-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-up text-emerald-400 text-sm"></i>
                <h3 class="font-semibold text-stone-200">Income Log</h3>
            </div>
            <div class="max-h-[500px] overflow-y-auto print:max-h-none">
                <table class="min-w-full table-dark">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order ID</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="text-xs text-stone-600">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="font-medium text-amber-400 text-sm">#{{ $order->order_code }}</td>
                            <td class="text-right font-semibold text-emerald-400 text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6">
                                <x-empty-state title="No income records" description="There are no orders within the selected report period." />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Expenses --}}
        <div class="rounded-2xl border border-stone-800 bg-stone-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-down text-red-400 text-sm"></i>
                <h3 class="font-semibold text-stone-200">Expense Log</h3>
            </div>
            <div class="max-h-[500px] overflow-y-auto print:max-h-none">
                <table class="min-w-full table-dark">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                        <tr>
                            <td class="text-xs text-stone-600">{{ \Carbon\Carbon::parse($exp->expense_date)->format('Y-m-d') }}</td>
                            <td class="font-medium text-stone-200 text-sm">{{ $exp->title }}</td>
                            <td class="text-right font-semibold text-red-400 text-sm">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6">
                                <x-empty-state title="No expense records" description="There are no expenses within the selected report period." />
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
