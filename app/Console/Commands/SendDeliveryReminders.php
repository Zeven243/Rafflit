<?php

namespace App\Console\Commands;

use App\Models\Listing;
use App\Models\User;
use App\Notifications\DeliveryConfirmedNotification;
use App\Notifications\DeliveryConfirmationReminder;
use Illuminate\Console\Command;

class SendDeliveryReminders extends Command
{
    protected $signature = 'reminders:send';

    protected $description = 'Send delivery reminders to winners and sellers';

    public function handle()
    {
        $listings = Listing::where('winner_user_id', '!=', null)
            ->where('delivery_confirmed', false)
            ->get();

        foreach ($listings as $listing) {
            $winner = User::find($listing->winner_user_id);
            $seller = $listing->seller;

            // Check if it has been 3 days since the last reminder or winner selection
            $lastReminderAt = $listing->deliveryReminders()->latest()->value('last_reminder_at');
            $reminderThreshold = $lastReminderAt ? $lastReminderAt->addDays(3) : $listing->updated_at->addDays(3);

            if (now()->greaterThanOrEqualTo($reminderThreshold)) {
                // Send reminder notifications to the winner and seller
                $winner->notify(new DeliveryConfirmationReminder($listing));
                $seller->notify(new DeliveryConfirmationReminder($listing));

                // Update the last reminder timestamp
                $listing->deliveryReminders()->updateOrCreate(
                    ['listing_id' => $listing->id, 'winner_id' => $winner->id],
                    ['last_reminder_at' => now()]
                );
            }

            // Check if it has been 7 days since the winner was selected
            if (now()->greaterThanOrEqualTo($listing->updated_at->addDays(7))) {
                // Send notification to the admin
                $admin = User::where('email', 'Admin@Rafflit.co.za')->first();
                $admin->notify(new DeliveryConfirmedNotification($listing, $winner, 0, 0));

                // Stop sending reminders for this listing
                $listing->deliveryReminders()->delete();
            }
        }
    }
}