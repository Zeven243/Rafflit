<?php

namespace App\Notifications;

use App\Models\Listings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WinnerNotification extends Notification
{
    use Queueable;

    public $listing;
    public $winner;
    public $seller;

    public function __construct(Listings $listing, User $winner, User $seller)
    {
        $this->listing = $listing;
        $this->winner = $winner;
        $this->seller = $seller;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Congratulations! You Won the Raffle')
            ->markdown('vendor.mail.notifications.winner_notification', [
                'listing' => $this->listing,
                'winner' => $this->winner,
                'seller' => $this->seller,
            ]);
    }
}
