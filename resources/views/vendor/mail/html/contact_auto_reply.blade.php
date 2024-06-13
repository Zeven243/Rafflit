@extends('vendor.mail.html.layout')

@section('title', 'Thank You for Contacting Rafflit')

@section('content')
    <h1>Thank You for Contacting Rafflit</h1>
    <p>Dear {{ $name }},</p>
    <p>Thank you for reaching out to us. We appreciate your interest in Rafflit and value your feedback.</p>
    <p>Our team will review your message and get back to you as soon as possible. We strive to provide prompt and helpful responses to all inquiries.</p>
    <p>In the meantime, feel free to explore our website and discover the exciting features and opportunities that Rafflit has to offer.</p>
    <p>Best regards,<br>The Rafflit Team</p>
@endsection
