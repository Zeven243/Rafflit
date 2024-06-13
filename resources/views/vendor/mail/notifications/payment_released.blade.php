@component('mail::message')
# Payment Released

The payment for your listing has been released.

Listing: {{ $listing->name }}
Amount: R{{ $listing->full_price * 0.9 }}

Your funds will be sent to you as soon as possible.

Thank you for using our platform!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
