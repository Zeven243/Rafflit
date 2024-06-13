<?php

namespace App\Notifications;

use App\Models\Listings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryConfirmationReminder extends Notification
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
            ->subject('Delivery Confirmation Reminder')
            ->markdown('vendor.mail.notifications.delivery_confirmation_reminder', [
                'listing' => $this->listing,
                'notifiable' => $notifiable,
            ]);
    }

    public function shouldSend($notifiable, $listing)
    {
        // Check if the delivery is not confirmed and the reminder has not been sent before
        return !$listing->delivery_confirmed && !$listing->deliveryReminders()->where('winner_id', $notifiable->id)->exists();
    }
}