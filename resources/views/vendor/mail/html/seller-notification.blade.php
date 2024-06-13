@extends('vendor.mail.html.layout')

@section('title', 'Raffle Sold Notification')

@section('styles')
    <style>
        .message {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .message p {
            margin-bottom: 10px;
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
@endsection

@section('content')
    <h1>Congratulations, {{ $seller->company }}!</h1>
    <p>Your item has been sold through a raffle on Rafflit. Here are the details:</p>
    <div class="message">
        <p><strong>Listing:</strong> {{ $listing->name }}</p>
        <p><strong>Description:</strong> {{ $listing->description }}</p>
        <p><strong>Full Price:</strong> R{{ $listing->full_price }}</p>
        <p><strong>Ticket Price:</strong> R{{ $listing->ticket_price }}</p>
    </div>
    
    <p>The winner of the raffle has been notified and provided with your contact information. They will reach out to you to arrange for delivery or pickup of the item.</p>
    
    <div class="message">
        <p><strong>Winner's Name:</strong> {{ $winner->first_name }} {{ $winner->last_name }}</p>
        <p><strong>Winner's Email:</strong> {{ $winner->email }}</p>
        <p><strong>Winner's Phone:</strong> {{ $winner->phone }}</p>
    </div>
    
    <p>Please note that the delivery or pickup arrangement is solely between you and the winner. Rafflit is not responsible for handling the delivery process.</p>
    
    <p>Your payment will be processed and released to you once the winner confirms the delivery of the item. If the winner does not confirm the delivery within 7 days, your payment will be automatically released.</p>
    
    <p>If you have any questions or concerns, please contact our support team for assistance.</p>
    
    <a href="mailto:support@rafflit.co.za" class="button">Contact Support</a>
    
    <p>Thank you for using Rafflit to sell your item. We appreciate your business!</p>
@endsection
