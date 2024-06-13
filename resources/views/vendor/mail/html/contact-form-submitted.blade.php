@extends('vendor.mail.html.layout')

@section('title', 'New Contact Form Submission')

@section('content')
    <h2>New Contact Form Submission</h2>

    <p><strong>Name:</strong> {{ $formData['name'] }}</p>
    <p><strong>Email:</strong> {{ $formData['email'] }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $formData['message'] }}</p>
@endsection
