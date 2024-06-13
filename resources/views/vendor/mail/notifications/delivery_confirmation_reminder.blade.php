@component('mail::message')
# Delivery Confirmation Reminder

This is a friendly reminder to confirm the delivery for the following listing:

Listing: {{ $listing->name }}

Please confirm the delivery by clicking the button below:

@component('mail::button', ['url' => route('confirm-delivery', ['listing' => $listing->id, 'winner' => $notifiable->id])])
Confirm Delivery
@endcomponent

If you have already confirmed delivery for the item, please contact Support@rafflit.co.za.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
