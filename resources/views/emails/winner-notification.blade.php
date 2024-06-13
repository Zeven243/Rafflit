<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffle Winner Notification</title>
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
        <div class="header">
            <img src="{{ asset('storage/Raffle_Winner.png') }}" alt="Raffle Winner">
        </div>
        <h1>Congratulations, {{ $firstName }} {{ $lastName }}!</h1>
        <p>You have won the raffle for the following listing:</p>
        <div class="message">
            <p><strong>Listing:</strong> {{ $listing->name }}</p>
            <p><strong>Description:</strong> {{ $listing->description }}</p>
            <p><strong>Full Price:</strong> R{{ $fullPrice }}</p>
            <p><strong>Ticket Price:</strong> R{{ $ticketPrice }}</p>
        </div>
        
        <p>As the winner, you are responsible for the shipping fees associated with the delivery of your prize. Please contact the seller to arrange for either delivery or pickup of the item.</p>
        
        <div class="message">
            <p><strong>Seller's Name:</strong> {{ $sellerName }}</p>
            <p><strong>Seller's Email:</strong> {{ $sellerEmail }}</p>
            <p><strong>Seller's Phone:</strong> {{ $sellerPhone }}</p>
        </div>
        
        <p>Please note that the delivery or pickup arrangement is solely between you and the seller. Rafflit is not responsible for handling the delivery process.</p>
        
        <p>To ensure a secure transaction and protect both parties, we recommend the following steps:</p>
        <ol>
            <li>Contact the seller and agree on a suitable delivery or pickup method.</li>
            <li>Once the item is received, confirm the delivery by clicking the button below.</li>
            <li>If you do not confirm the delivery within 7 days, the seller's payment will be automatically released.</li>
        </ol>
        
        <a href="{{ route('confirm-delivery', ['listing' => $listing->id, 'winner' => $winner->id]) }}" class="button">Confirm Item Received</a>
        
        <p>Once you confirm the delivery, the seller will receive their payment from Rafflit.</p>
        
        <p>If you have any issues or concerns during the process, please contact our support team for assistance.</p>
        
        <p>Thank you for participating in the raffle and congratulations on your win!</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Rafflit. All rights reserved.
        </div>
    </div>
</body>
</html>