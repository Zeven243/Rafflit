<?php

namespace App\Notifications;

use App\Models\Listings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReleasedNotification extends Notification
{
    use Queueable;

    public $listing;

    public function __construct(Listings $listing)
    {
        $this->listing = $listing;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Released')
            ->markdown('vendor.mail.notifications.payment_released', [
                'listing' => $this->listing,
            ]);
    }
}
