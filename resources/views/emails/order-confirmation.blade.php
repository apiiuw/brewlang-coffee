<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation — Brewlang Coffee</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0c0a09;
            color: #d6d3d1;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #1c1917 0%, #292524 100%);
            border: 1px solid #44403c;
            border-radius: 20px 20px 0 0;
            padding: 36px 32px;
            text-align: center;
        }
        .logo-icon {
            display: inline-block;
            margin-bottom: 14px;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #fafaf9;
            letter-spacing: -0.02em;
        }
        .header-sub {
            margin-top: 6px;
            font-size: 13px;
            color: #78716c;
        }
        /* Body card */
        .body-card {
            background: #1c1917;
            border: 1px solid #292524;
            border-top: none;
            padding: 32px;
        }
        .greeting {
            font-size: 16px;
            color: #d6d3d1;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        /* Order code box */
        .order-box {
            background: rgba(251, 191, 36, 0.05);
            border: 1px solid rgba(251, 191, 36, 0.20);
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            margin-bottom: 28px;
        }
        .order-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #78716c;
            margin-bottom: 8px;
        }
        .order-code {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 0.1em;
            color: #fbbf24;
            line-height: 1;
        }
        .order-meta {
            margin-top: 14px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .meta-pill {
            background: rgba(255,255,255,0.04);
            border: 1px solid #44403c;
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 13px;
            color: #a8a29e;
        }
        .meta-pill strong {
            color: #e7e5e4;
            font-weight: 600;
        }
        /* Section header */
        .section-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #78716c;
            margin-bottom: 14px;
        }
        /* Items table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        thead th {
            background: #292524;
            color: #78716c;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 10px 14px;
            text-align: left;
        }
        tbody td {
            padding: 12px 14px;
            font-size: 14px;
            color: #d6d3d1;
            border-bottom: 1px solid #292524;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .total-row td {
            padding: 14px;
            background: #292524;
            border-top: 1px solid #44403c;
        }
        .total-label {
            font-size: 14px;
            font-weight: 700;
            color: #a8a29e;
            text-align: right;
        }
        .total-amount {
            font-size: 18px;
            font-weight: 900;
            color: #fbbf24;
            text-align: right;
        }
        /* Info box */
        .info-box {
            background: rgba(52, 211, 153, 0.05);
            border: 1px solid rgba(52, 211, 153, 0.18);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 24px;
            font-size: 14px;
            color: #6ee7b7;
            line-height: 1.6;
        }
        /* Footer */
        .footer {
            background: #0c0a09;
            border: 1px solid #292524;
            border-top: none;
            border-radius: 0 0 20px 20px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #44403c;
            line-height: 1.7;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="logo-icon">
            <img src="{{ $message->embed(public_path('images/logo-brewlang.png')) }}" alt="Brewlang Coffee" style="width: 52px; height: 52px; object-fit: contain;">
        </div>
        <div class="brand-name">Brewlang Coffee</div>
        <div class="header-sub">Order Confirmation</div>
    </div>

    {{-- Body --}}
    <div class="body-card">
        <p class="greeting">
            Hi <strong style="color:#e7e5e4;">{{ $order->customer_name }}</strong>,<br>
            Thank you for your order! Please proceed to the cashier to complete your payment and show them your order code.
        </p>

        {{-- Order Code --}}
        <div class="order-box">
            <div class="order-label">Your Order Code</div>
            <div class="order-code">{{ $order->order_code }}</div>
            <div class="order-meta">
                <span class="meta-pill">Table <strong>{{ $order->table_number }}</strong></span>
                <span class="meta-pill">Status: <strong style="color:#facc15;">Unpaid</strong></span>
            </div>
        </div>

        {{-- Items --}}
        <div class="section-header">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#e7e5e4;">{{ $item->menu_name_snapshot }}</div>
                        @if($item->item_note)
                        <div style="font-size:12px;color:#57534e;margin-top:2px;">Note: {{ $item->item_note }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;color:#a8a29e;">× {{ $item->quantity }}</td>
                    <td style="text-align:right;font-weight:600;color:#e7e5e4;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="total-label">Total Amount</td>
                    <td class="total-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="info-box">
            ✓ &nbsp;Show your order code <strong>{{ $order->order_code }}</strong> to our staff. No need to print — just show this email or take a screenshot.
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            This is an automated message from Brewlang Coffee.<br>
            &copy; {{ date('Y') }} Brewlang Coffee. All rights reserved.
        </p>
    </div>

</div>
</body>
</html>
