<?php

namespace App\Notifications;

use App\Models\Listings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerNotification extends Notification
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
        $seller = $notifiable;
        $winner = User::findOrFail($this->listing->winner_user_id);

        return (new MailMessage)
            ->subject('Raffle Winner Notification')
            ->markdown('vendor.mail.notifications.seller_notification', [
                'listing' => $this->listing,
                'winner' => $winner,
                'seller' => $seller,
            ]);
    }
}
