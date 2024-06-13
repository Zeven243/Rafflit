<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactFormAutoReply;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Mail;

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
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Generate a unique ticket number
        $ticketNumber = SupportTicket::generateTicketNumber();

        // Save the support ticket to the database
        $supportTicket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Send email to Support@Rafflit.co.za
        $supportEmail = 'Support@Rafflit.co.za';
        Mail::to($supportEmail)->send(new ContactFormSubmitted($request->all(), $ticketNumber));

        // Send auto-reply email to the user
        $userEmail = $request->email;
        Mail::to($userEmail)->send(new ContactFormAutoReply($request->all()));

        return redirect()->back()->with('success', 'Thank you for contacting us. We will get back to you soon!');
    }
}