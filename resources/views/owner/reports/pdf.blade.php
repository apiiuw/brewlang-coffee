<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewlang Financial Report</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.45;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 24px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 16px;
        }

        .eyebrow {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #92400e;
            margin-bottom: 8px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 11px;
            color: #4b5563;
        }

        .summary {
            width: 100%;
            margin-bottom: 24px;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
        }

        .summary-card {
            width: 33.33%;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 14px;
            vertical-align: top;
            background: #f9fafb;
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .muted {
            color: #6b7280;
        }

        .empty {
            padding: 14px;
            border: 1px dashed #d1d5db;
            color: #6b7280;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="eyebrow">Finance</p>
        <h1 class="title">Brewlang Financial Report</h1>
        <p class="subtitle">
            Period: {{ \Carbon\Carbon::parse($date_from)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($date_to)->format('M d, Y') }}
        </p>
    </div>

    <table class="summary">
        <tr>
            <td class="summary-card">
                <p class="summary-label">Income</p>
                <p class="summary-value">Rp {{ number_format($total_income, 0, ',', '.') }}</p>
            </td>
            <td class="summary-card">
                <p class="summary-label">Expenses</p>
                <p class="summary-value">Rp {{ number_format($total_expenses, 0, ',', '.') }}</p>
            </td>
            <td class="summary-card">
                <p class="summary-label">Net Result</p>
                <p class="summary-value">Rp {{ number_format($total_income - $total_expenses, 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2 class="section-title">Income Log</h2>
        @if ($orders->isEmpty())
            <div class="empty">No income records for the selected period.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 28%;">Date</th>
                        <th style="width: 32%;">Order ID</th>
                        <th class="text-right" style="width: 40%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $order->order_code }}</td>
                            <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section">
        <h2 class="section-title">Expense Log</h2>
        @if ($expenses->isEmpty())
            <div class="empty">No expense records for the selected period.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 28%;">Date</th>
                        <th style="width: 42%;">Item</th>
                        <th class="text-right" style="width: 30%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                            <td>{{ $expense->title }}</td>
                            <td class="text-right">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
