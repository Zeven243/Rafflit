<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Listings;
use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use App\Notifications\WinnerNotification;
use Illuminate\Support\Facades\DB;
use App\Notifications\SellerNotification;
use App\Notifications\DeliveryConfirmedNotification;
use App\Notifications\DeliveryConfirmationReminder;

class RaffleEntryController extends Controller
{
    /**
     * Store a raffle entry for the specified listing.
     */
    public function storeRaffleEntry(Request $request, $listingId)
    {
        $listing = Listings::findOrFail($listingId);

        if ($listing->tickets_sold < $listing->amount_of_tickets) {
            $quantity = $request->input('quantity', 1);

            DB::transaction(function () use ($listingId, $quantity) {
                $listing = Listings::lockForUpdate()->findOrFail($listingId);

                if ($listing->tickets_sold + $quantity <= $listing->amount_of_tickets) {
                    $listing->increment('tickets_sold', $quantity);

                    for ($i = 0; $i < $quantity; $i++) {
                        RaffleEntry::create([
                            'user_id' => auth()->user()->id,
                            'listing_id' => $listingId,
                        ]);
                    }

                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($listing)
                        ->log('entered raffle');

                    // Check if all tickets are sold after the raffle entry
                    if ($listing->tickets_sold >= $listing->amount_of_tickets) {
                        $this->selectWinner($listingId);
                    }
                } else {
                    return redirect()->back()->with('error', 'Not enough tickets available.');
                }
            });

            return redirect()->back()->with('success', 'Raffle entry added to cart successfully.');
        } else {
            return redirect()->back()->with('error', 'All tickets have been sold.');
        }
    }

    public function getRaffleEntries()
    {
        $user = auth()->user();
        $raffleEntries = $user->raffleEntries()->with('listing')->get();
        $allListings = Listings::all();

        return Inertia::render('Dashboard', [
            'raffleEntries' => $raffleEntries,
            'allListings' => $allListings,
        ]);
    }

    public function showRaffleEntries()
    {
        $user = auth()->user();
        $raffleEntries = $user->raffleEntries()->with('listing')->get();
        $wonListings = Listings::where('winner_user_id', $user->id)->get();

        return Inertia::render('Listings/RaffleEntriesPage', [
            'raffleEntries' => $raffleEntries,
            'wonListings' => $wonListings,
        ]);
    }

    public function selectWinner($listingId)
    {
        $listing = Listings::findOrFail($listingId);
    
        if ($listing->tickets_sold >= $listing->amount_of_tickets) {
            // Get the raffle entries for the listing
            $entries = RaffleEntry::where('listing_id', $listingId)->get();
    
            if ($entries->isEmpty()) {
                // Handle the case where there are no entries
                return redirect()->back()->with('error', 'No raffle entries available.');
            }
    
            // Create an array to hold the raffle entry IDs
            $entryIds = $entries->pluck('id')->toArray();
    
            // Select a random winner from the raffle entry IDs array
            $winnerEntryId = $entryIds[array_rand($entryIds)];
    
            // Get the winner user ID from the selected raffle entry
            $winnerUserId = RaffleEntry::findOrFail($winnerEntryId)->user_id;
    
            // Update the listing with the winner user ID and set is_active to false
            $listing->update([
                'winner_user_id' => $winnerUserId,
                'is_active' => false,
            ]);
    
            // Fetch the winner user details
            $winnerUser = User::findOrFail($winnerUserId);
    
            // Get the seller details
            $seller = User::findOrFail($listing->user_id);
    
            // Send email notification to the winner
            $winnerUser->notify(new WinnerNotification($listing, $winnerUser, $seller));
    
            // Send email notification to the seller
            $seller->notify(new SellerNotification($listing));
    
            activity()
                ->causedBy(auth()->user())
                ->performedOn($listing)
                ->withProperties(['winner_id' => $winnerUserId, 'winner_name' => $winnerUser->name])
                ->log('selected winner');
    
            // Schedule the delivery reminders
            $this->scheduleDeliveryReminders($listing);
    
            return redirect()->back()->with('success', 'Winner selected successfully.');
        }
    
        return redirect()->back()->with('error', 'Not all tickets have been sold yet.');
    }
    

    public function confirmDelivery($listingId, $winnerId)
    {
        $listing = Listings::findOrFail($listingId);
        $winner = User::findOrFail($winnerId);
    
        // Check if the delivery is not already confirmed
        if (!$listing->delivery_confirmed) {
            // Update the listing status to indicate delivery confirmation
            $listing->delivery_confirmed = true;
            $listing->save();
    
            // Calculate the amount to be sent to the seller (90% of the full price)
            $sellerAmount = $listing->full_price * 0.9;
            $adminFee = $listing->full_price - $sellerAmount;
    
            // Send email notification to the admin
            $admin = User::where('email', 'Admin@Rafflit.co.za')->first();
            $admin->notify(new DeliveryConfirmedNotification($listing, $winner, $sellerAmount, $adminFee));
    
            // Delete the delivery reminders for the listing
            $listing->deliveryReminders()->delete();
    
            // Redirect the winner to a success page or show a success message
            return redirect()->route('raffle-entries.show')->with('success', 'Delivery confirmed successfully. Thank you for your purchase!');
        } else {
            // Delivery already confirmed, redirect back with an appropriate message
            return redirect()->back()->with('info', 'Delivery has already been confirmed for this listing.');
        }
    }
    
    private function scheduleDeliveryReminders(Listings $listing)
    {
        $winner = $listing->winner;
        $seller = $listing->seller;
    
        // Schedule the delivery reminders
        $reminderDelay = now()->addDays(3);
        $maxReminders = 2; // Send a maximum of 2 reminders
    
        for ($i = 1; $i <= $maxReminders; $i++) {
            // Schedule the reminder for the winner
            $winner->notify((new DeliveryConfirmationReminder($listing))->delay($reminderDelay));
    
            // Schedule the reminder for the seller
            $seller->notify((new DeliveryConfirmationReminder($listing))->delay($reminderDelay));
    
            // Increment the reminder delay by 3 days for the next reminder
            $reminderDelay->addDays(3);
        }
    
        // Schedule the admin notification after 7 days if delivery is not confirmed
        $adminNotificationDelay = now()->addDays(7);
        $admin = User::where('email', 'Admin@Rafflit.co.za')->first();
        $admin->notify((new DeliveryConfirmedNotification($listing, $winner, 0, 0))->delay($adminNotificationDelay));
    }
}