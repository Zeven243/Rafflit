<?php

namespace App\Notifications;

use App\Models\Listings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryConfirmedNotification extends Notification
{
    use Queueable;

    public $listing;
    public $winner;
    public $seller;
    public $sellerAmount;
    public $adminFee;

    public function __construct(Listings $listing, User $winner, $sellerAmount, $adminFee)
    {
        $this->listing = $listing;
        $this->winner = $winner;
        $this->seller = $listing->user;
        $this->sellerAmount = $sellerAmount;
        $this->adminFee = $adminFee;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Delivery Confirmed')
            ->markdown('vendor.mail.notifications.delivery_confirmed', [
                'listing' => $this->listing,
                'winner' => $this->winner,
                'seller' => $this->seller,
                'sellerAmount' => $this->sellerAmount,
                'adminFee' => $this->adminFee,
            ]);
    }
}
