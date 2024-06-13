<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $ticketNumber;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string $ticketNumber
     */
    public function __construct($data, $ticketNumber)
    {
        $this->data = $data;
        $this->ticketNumber = $ticketNumber;
        $this->subject = "Ticket #{$ticketNumber} - {$data['subject']}";
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('noreply@rafflit.co.za', 'Rafflit')
                    ->subject("Ticket #{$this->ticketNumber} - {$this->data['subject']}")
                    ->markdown('emails.contact_form_submitted')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'subject' => $this->data['subject'],
                        'message' => $this->data['message'],
                        'ticketNumber' => $this->ticketNumber,
                    ]);
    }
}
