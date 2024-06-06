<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('ContactUs');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Send email to support@rafflit.co.za
        Mail::to('support@rafflit.co.za')->send(new ContactFormSubmitted($request->all()));

        return redirect()->back()->with('success', 'Thank you for contacting us. We will get back to you soon!');
    }
}
