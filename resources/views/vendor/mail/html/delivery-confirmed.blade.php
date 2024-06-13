@extends('vendor.mail.html.layout')

@section('title', 'Delivery Confirmed')

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
    </style>
@endsection

@section('content')
    <h1>Delivery Confirmed</h1>
    <p>The delivery for the following listing has been confirmed:</p>
    <h2>Listing Details</h2>
    <div class="message">
        <p><strong>Name:</strong> {{ $listing->name }}</p>
        <p><strong>Description:</strong> {{ $listing->description }}</p>
        <p><strong>Full Price:</strong> R{{ $listing->full_price }}</p>
        <p><strong>Ticket Price:</strong> R{{ $listing->ticket_price }}</p>
        <p><strong>Amount of Tickets:</strong> {{ $listing->amount_of_tickets }}</p>
        <p><strong>Tickets Sold:</strong> {{ $listing->tickets_sold }}</p>
        <p><strong>Company:</strong> {{ $listing->company }}</p>
        <p><strong>SKU:</strong> {{ $listing->SKU }}</p>
    </div>
    <h2>Seller Details</h2>
    <div class="message">
        <p><strong>Name:</strong> {{ $seller->first_name }} {{ $seller->last_name }}</p>
        <p><strong>Email:</strong> {{ $seller->email }}</p>
        <p><strong>Phone:</strong> {{ $seller->phone }}</p>
        <p><strong>Company:</strong> {{ $seller->company }}</p>
        <p><strong>VAT Number:</strong> {{ $seller->vat_number }}</p>
        <p><strong>Selling Preference:</strong> {{ $seller->selling_preference }}</p>
        <p><strong>Shipping Address:</strong> {{ $seller->shipping_address }}</p>
    </div>
    <h2>Winner Details</h2>
    <div class="message">
        <p><strong>Name:</strong> {{ $winner->first_name }} {{ $winner->last_name }}</p>
        <p><strong>Email:</strong> {{ $winner->email }}</p>
        <p><strong>Phone:</strong> {{ $winner->phone }}</p>
    </div>
    <h2>Payment Details</h2>
    <div class="message">
        <p><strong>Amount to be sent to the seller:</strong> R{{ $sellerAmount }}</p>
        <p><strong>Admin fee (10%):</strong> R{{ $adminFee }}</p>
    </div>
    <p>Please send the payment to the seller as soon as possible.</p>
@endsection
