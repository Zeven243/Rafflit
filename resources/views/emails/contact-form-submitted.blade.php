@component('mail::message')
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff;">
    <tr>
        <td align="center" style="padding: 40px;">
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <h1 style="color: #333333; font-size: 24px; margin-bottom: 20px; text-align: center;">New Support Ticket</h1>
            <p style="color: #555555; font-size: 16px; line-height: 1.5; margin-bottom: 20px;">Hello,</p>
            <p style="color: #555555; font-size: 16px; line-height: 1.5; margin-bottom: 20px;">You have received a new support ticket from the Rafflit website. Here are the details:</p>
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8f8f8; border-radius: 5px; padding: 20px; margin-bottom: 30px;">
                <tr>
                    <td>
                        <p style="margin-bottom: 10px;"><strong>Ticket Number:</strong> {{ $ticketNumber }}</p>
                        <p style="margin-bottom: 10px;"><strong>Subject:</strong> {{ $subject }}</p>
                        <p style="margin-bottom: 10px;"><strong>Name:</strong> {{ $name }}</p>
                        <p style="margin-bottom: 10px;"><strong>Email:</strong> {{ $email }}</p>
                        <p style="margin-bottom: 10px;"><strong>Message:</strong></p>
                        <p style="margin-bottom: 10px;">{{ $message }}</p>
                    </td>
                </tr>
            </table>
            <p style="color: #555555; font-size: 16px; line-height: 1.5; margin-bottom: 20px;">Please respond to this inquiry as soon as possible.</p>
            @component('mail::button', ['url' => 'https://rafflit.co.za/support'])
                View Ticket
            @endcomponent
            <p style="color: #555555; font-size: 16px; line-height: 1.5; margin-bottom: 20px;">Best regards,<br>The Rafflit Team</p>
        </td>
    </tr>
</table>
@endcomponent