<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Rafflit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            color: #888888;
            font-size: 14px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/Heading.png') }}" alt="Rafflit Heading">
        </div>
        <h1>Thank You for Contacting Rafflit</h1>
        <p>Dear {{ $name }},</p>
        <p>Thank you for reaching out to us. We appreciate your interest in Rafflit and value your feedback.</p>
        <p>Our team will review your message and get back to you as soon as possible. We strive to provide prompt and helpful responses to all inquiries.</p>
        <p>In the meantime, feel free to explore our website and discover the exciting features and opportunities that Rafflit has to offer.</p>
        <p>Best regards,<br>The Rafflit Team</p>
        <div class="footer">
            &copy; {{ date('Y') }} Rafflit. All rights reserved.
        </div>
    </div>
</body>
</html>
