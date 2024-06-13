@component('mail::message')
# Congratulations, {{ $winner->first_name }} {{ $winner->last_name }}!

You have won the raffle for the following listing:

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td class="panel-content">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="panel-item">
                        <strong>Listing:</strong> {{ $listing->name }}<br>
                        <strong>Description:</strong> {{ $listing->description }}<br>
                        <strong>Full Price:</strong> R{{ $listing->full_price }}<br>
                        <strong>Ticket Price:</strong> R{{ $listing->ticket_price }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

As the winner, you are responsible for the shipping fees associated with the delivery of your prize. Please contact the seller to arrange for either delivery or pickup of the item.

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td class="panel-content">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="panel-item">
                        <strong>Seller's Name:</strong> {{ $seller->company }}<br>
                        <strong>Seller's Email:</strong> {{ $seller->email }}<br>
                        <strong>Seller's Phone:</strong> {{ $seller->phone }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

Please note that the delivery or pickup arrangement is solely between you and the seller. Rafflit is not responsible for handling the delivery process.

To ensure a secure transaction and protect both parties, we recommend the following steps:
1. Contact the seller and agree on a suitable delivery or pickup method.
2. Once the item is received, confirm the delivery by clicking the button below.
3. If you do not confirm the delivery within 7 days, the seller's payment will be automatically released.

@component('mail::button', ['url' => route('confirm-delivery', ['listing' => $listing->id, 'winner' => $winner->id])])
Confirm Item Received
@endcomponent

Once you confirm the delivery, the seller will receive their payment from Rafflit.

If you have any issues or concerns during the process, please contact our support team for assistance.

Thank you for participating in the raffle and congratulations on your win!

Thanks,<br>
{{ config('app.name') }}
@endcomponent