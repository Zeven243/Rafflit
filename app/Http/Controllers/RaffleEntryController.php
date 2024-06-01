<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use App\Models\RaffleEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RaffleEntryController extends Controller
{
    /**
     * Store a raffle entry for the specified listing.
     */
    public function storeRaffleEntry(Request $request, $listingId)
    {
        DB::transaction(function () use ($listingId) {
            $listing = Listings::lockForUpdate()->findOrFail($listingId);

            if ($listing->tickets_sold < $listing->amount_of_tickets) {
                $listing->increment('tickets_sold');

                RaffleEntry::create([
                    'user_id' => auth()->user()->id,
                    'listing_id' => $listingId,
                ]);

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($listing)
                    ->log('entered raffle');

                // Check if all tickets are sold after the raffle entry
                if ($listing->tickets_sold >= $listing->amount_of_tickets) {
                    $this->selectWinner($listingId);
                }
            } else {
                return redirect()->route('dashboard')->with('error', 'All tickets have been sold.');
            }
        });

        return redirect()->route('dashboard')->with('success', 'Entered raffle successfully.');
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

            // Get the winner user details
            $winnerUser = User::findOrFail($winnerUserId);

            // Implement notification logic here

            activity()
                ->causedBy(auth()->user())
                ->performedOn($listing)
                ->withProperties(['winner_id' => $winnerUserId, 'winner_name' => $winnerUser->name])
                ->log('selected winner');

            return redirect()->back()->with('success', 'Winner selected successfully.');
        }

        return redirect()->back()->with('error', 'Not all tickets have been sold yet.');
    }
}
