@extends('vendor.mail.html.layout')

@section('title', 'New Support Ticket')

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
    <h1>New Support Ticket</h1>
    <p>Hello,</p>
    <p>You have received a new support ticket from the Rafflit website. Here are the details:</p>
    <div class="message">
        <p><strong>Ticket Number:</strong> {{ $ticketNumber }}</p>
        <p><strong>Subject:</strong> {{ $subject }}</p>
        <p><strong>Name:</strong> {{ $name }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
        <p><strong>Message:</strong></p>
        <p>{{ $message }}</p>
    </div>
    <p>Please respond to this inquiry as soon as possible.</p>
    <a href="https://rafflit.co.za/support" class="button">View Ticket</a>
    <p>Best regards,<br>The Rafflit Team</p>
@endsection
