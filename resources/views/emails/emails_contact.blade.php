<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Support Ticket</title>
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
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto;
            max-width: 120px;
            height: auto;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
        }
        .message {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .message p {
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            color: #888888;
            font-size: 14px;
            margin-top: 40px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('storage/raffl-logo.png') }}" alt="Rafflit Logo" class="logo">
        <h1>New Support Ticket</h1>
        <p>Hello,</p>
        <p>You have received a new support ticket from the Rafflit website. Here are the details:</p>
        <div class="message">
            <p><strong>Ticket Number:</strong> {{ $ticketNumber }}</p>
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
            <p><strong>Message:</strong></p>
            <p>{{ $message }}</p>
        </div>
        <p>Please respond to this inquiry as soon as possible.</p>
        <a href="https://rafflit.co.za/support" class="button">View Ticket</a>
        <p>Best regards,<br>The Rafflit Team</p>
        <div class="footer">
            &copy; {{ date('Y') }} Rafflit. All rights reserved.
        </div>
    </div>
</body>
</html>
