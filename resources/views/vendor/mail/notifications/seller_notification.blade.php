@component('mail::message')
# Congratulations, {{ $seller->company }}!

Your item has been sold through a raffle on Rafflit. Here are the details:

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

The winner of the raffle has been notified and provided with your contact information. They will reach out to you to arrange for delivery or pickup of the item.

<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td class="panel-content">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="panel-item">
                        <strong>Winner's Name:</strong> {{ $winner->first_name }} {{ $winner->last_name }}<br>
                        <strong>Winner's Email:</strong> {{ $winner->email }}<br>
                        <strong>Winner's Phone:</strong> {{ $winner->phone }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

Please note that the delivery or pickup arrangement is solely between you and the winner. Rafflit is not responsible for handling the delivery process.

Your payment will be processed and released to you once the winner confirms the delivery of the item. If the winner does not confirm the delivery within 7 days, your payment will be automatically released.

If you have any questions or concerns, please contact our support team for assistance.

@component('mail::button', ['url' => 'mailto:support@rafflit.co.za'])
Contact Support
@endcomponent

Thank you for using Rafflit to sell your item. We appreciate your business!

Thanks,<br>
{{ config('app.name') }}
@endcomponent