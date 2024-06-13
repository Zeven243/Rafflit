@component('mail::message')
# Delivery Confirmed

The delivery for the following listing has been confirmed:

## Listing Details
- Name: {{ $listing->name }}
- Description: {{ $listing->description }}
- Full Price: R{{ $listing->full_price }}
- Ticket Price: R{{ $listing->ticket_price }}
- Amount of Tickets: {{ $listing->amount_of_tickets }}
- Tickets Sold: {{ $listing->tickets_sold }}
- Company: {{ $listing->company }}
- SKU: {{ $listing->SKU }}

## Seller Details
- Name: {{ $seller->first_name }} {{ $seller->last_name }}
- Email: {{ $seller->email }}
- Phone: {{ $seller->phone }}
- Company: {{ $seller->company }}
- VAT Number: {{ $seller->vat_number }}
- Selling Preference: {{ $seller->selling_preference }}
- Shipping Address: {{ $seller->shipping_address }}

## Winner Details
- Name: {{ $winner->first_name }} {{ $winner->last_name }}
- Email: {{ $winner->email }}
- Phone: {{ $winner->phone }}

## Payment Details
- Amount to be sent to the seller: R{{ $sellerAmount }}
- Admin fee (10%): R{{ $adminFee }}

Please send the payment to the seller as soon as possible.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
