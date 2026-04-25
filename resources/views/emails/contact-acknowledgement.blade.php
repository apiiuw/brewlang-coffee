<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Received Your Message - Brewlang Coffee</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0c0a09; color: #e7e5e4; padding: 24px; }
        .card { max-width: 640px; margin: 0 auto; background: #1c1917; border: 1px solid #292524; border-radius: 16px; padding: 28px; }
        .label { color: #fbbf24; font-size: 12px; text-transform: uppercase; letter-spacing: .16em; }
        .title { font-size: 24px; margin: 10px 0 18px; color: #fafaf9; }
        .body { line-height: 1.7; color: #d6d3d1; }
        .box { color: #fafaf9; margin-top: 18px; padding: 16px; background: #0c0a09; border: 1px solid #292524; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="label">Contact Form</div>
        <div class="title">Thanks, {{ $contact['name'] }}</div>
        <div class="body">
            We received your message and will get back to you soon at this email address.
        </div>
        <div class="box">
            {{ $contact['message'] }}
        </div>
    </div>
</body>
</html>
