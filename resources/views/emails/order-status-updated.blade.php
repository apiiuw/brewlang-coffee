<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update — Brewlang Coffee</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0c0a09;
            color: #d6d3d1;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper { max-width: 560px; margin: 0 auto; }

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
        .header-sub { margin-top: 6px; font-size: 13px; color: #78716c; }

        .body-card {
            background: #1c1917;
            border: 1px solid #292524;
            border-top: none;
            padding: 36px 32px;
        }

        .greeting { font-size: 15px; color: #d6d3d1; margin-bottom: 28px; line-height: 1.7; }

        .status-block {
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            margin-bottom: 28px;
        }

        .order-code-small {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #78716c;
            margin-bottom: 10px;
        }
        .order-code-value {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 0.08em;
            color: #fbbf24;
            margin-bottom: 18px;
        }
        .status-label {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            padding: 3px 0;
            margin-bottom: 10px;
        }
        .status-value {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.01em;
            line-height: 1.1;
        }
        .status-message {
            margin-top: 12px;
            font-size: 14px;
            line-height: 1.7;
        }

        /* Status color variants */
        .status-paid    { background: rgba(59,130,246,0.07); border: 1px solid rgba(59,130,246,0.20); }
        .status-paid .status-value    { color: #93c5fd; }
        .status-paid .status-label    { color: #60a5fa; }
        .status-paid .status-message  { color: #93c5fd; }

        .status-in-progress { background: rgba(251,146,60,0.07); border: 1px solid rgba(251,146,60,0.22); }
        .status-in-progress .status-value    { color: #fdba74; }
        .status-in-progress .status-label    { color: #fb923c; }
        .status-in-progress .status-message  { color: #fdba74; }

        .status-done { background: rgba(52,211,153,0.07); border: 1px solid rgba(52,211,153,0.22); }
        .status-done .status-value    { color: #6ee7b7; }
        .status-done .status-label    { color: #34d399; }
        .status-done .status-message  { color: #6ee7b7; }

        .status-default { background: rgba(255,255,255,0.03); border: 1px solid #44403c; }
        .status-default .status-value    { color: #e7e5e4; }
        .status-default .status-label    { color: #78716c; }
        .status-default .status-message  { color: #a8a29e; }

        .footer {
            background: #0c0a09;
            border: 1px solid #292524;
            border-top: none;
            border-radius: 0 0 20px 20px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p { font-size: 12px; color: #44403c; line-height: 1.7; }
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
        <div class="header-sub">Order Status Update</div>
    </div>

    {{-- Body --}}
    <div class="body-card">
        <p class="greeting">
            Hi <strong style="color:#e7e5e4;">{{ $order->customer_name }}</strong>,<br>
            Here's an update on your order. We'll keep you posted as it progresses.
        </p>

        {{-- Status block --}}
        @php
            $statusClass = match($order->status) {
                'paid'        => 'status-paid',
                'in_progress' => 'status-in-progress',
                'all_done'    => 'status-done',
                default       => 'status-default',
            };
            $statusMessage = match($order->status) {
                'paid'        => 'Your payment has been confirmed ✓ Our team is getting your order ready.',
                'in_progress' => 'Your order is now being prepared by our barista. Hang tight!',
                'all_done'    => 'Your order is ready! Please pick it up at the counter.',
                default       => 'Please check with our staff for the latest status.',
            };
        @endphp

        <div class="status-block {{ $statusClass }}">
            <div class="order-code-small">Order Code</div>
            <div class="order-code-value">{{ $order->order_code }}</div>
            <div class="status-label">Status</div>
            <div class="status-value">{{ $order->status_label }}</div>
            <div class="status-message">{{ $statusMessage }}</div>
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
