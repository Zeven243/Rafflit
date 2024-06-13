@component('mail::message')
# New Support Ticket

Hello,

You have received a new support ticket from the Rafflit website. Here are the details:

@component('mail::panel')
Ticket Number: {{ $ticketNumber }}
Subject: {{ $subject }}
Name: {{ $name }}
Email: {{ $email }}
Message:
{{ $message }}
@endcomponent

Please respond to this inquiry as soon as possible.

@component('mail::button', ['url' => 'https://rafflit.co.za/support'])
View Ticket
@endcomponent

Best regards,<br>
The Rafflit Team
@endcomponent
