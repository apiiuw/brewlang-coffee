<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message - Brewlang Coffee</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f1ea;
            color: #1c1917;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            padding: 32px 16px;
            box-sizing: border-box;
        }

        .card {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 20px;
            overflow: hidden;
        }

        .header {
            background: #1c1917;
            color: #ffffff;
            padding: 24px 28px;
        }

        .label {
            display: inline-block;
            margin-bottom: 10px;
            color: #fbbf24;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            color: #ffffff;
        }

        .content {
            padding: 28px;
        }

        .intro {
            margin: 0 0 18px;
            color: #44403c;
            line-height: 1.7;
        }

        .meta {
            margin: 0 0 18px;
            padding: 16px 18px;
            background: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: 14px;
        }

        .meta p {
            margin: 0 0 8px;
            color: #292524;
            line-height: 1.6;
        }

        .meta p:last-child {
            margin-bottom: 0;
        }

        .meta strong {
            color: #1c1917;
        }

        .message-label {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: bold;
            color: #78716c;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .message {
            white-space: pre-wrap;
            line-height: 1.8;
            color: #1c1917;
            background: #fff7ed;
            border: 1px solid #fdba74;
            border-radius: 14px;
            padding: 18px;
        }

        .footer {
            padding: 0 28px 28px;
            color: #78716c;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="label">Contact Form</div>
                <h1 class="title">New message from {{ $contact['name'] }}</h1>
            </div>
            <div class="content">
                <p class="intro">A visitor sent a new message through the Brewlang Coffee contact form. Details are below.</p>
                <div class="meta">
                    <p><strong>Name:</strong> {{ $contact['name'] }}</p>
                    <p><strong>Email:</strong> {{ $contact['email'] }}</p>
                </div>
                <p class="message-label">Message</p>
                <div class="message">{{ $contact['message'] }}</div>
            </div>
            <div class="footer">
                Please reply directly to the sender email above if you want to continue the conversation.
            </div>
        </div>
    </div>
</body>
</html>
