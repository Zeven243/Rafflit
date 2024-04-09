<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\RaffleEntry;
use Spatie\Activitylog\Models\Activity;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $listings = Listings::all();

        Inertia::share('listings', $listings);

        return Inertia::render('Listings/index', [
            'listings' => $listings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Listings/create', [
            'listings' => Listings::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric',
            'amount_of_tickets' => 'required|integer|min:1',
            'image' => 'required|image',
        ]);
    
        if ($request->hasFile('image')) {
            // Create an image manager instance with the desired driver
            $manager = new ImageManager(new Driver());
    
            // Make an image instance and resize it
            $image = $manager->read($request->file('image'));
            $image->resize(300, 200);

            // Generate a filename and save the image
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $image->save(public_path('storage/listings/' . $filename));
    
            $imagePath = 'storage/listings/' . $filename;
        } else {
            $imagePath = null;
        }
    
        $imagePath = 'storage/listings/' . $filename;
        $listing = Listings::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'amount_of_tickets' => $request->amount_of_tickets,
            'user_id' => auth()->user()->id,
            'image' => url($imagePath),
        ]);
    
        // Log the listing creation activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($listing)
            ->log('created');

        return redirect()->route('listings.index')->with('success', 'Listing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listings $listing)
    {
        return Inertia::render('Listings/show', [
            'listing' => $listing->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listings $listing)
    {
        return Inertia::render('Listings/edit', [
            'listing' => $listing,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listings $listing)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required',
        ]);

        $listing->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // Log the listing update activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($listing)
            ->log('updated');

        return redirect()->route('listings.index')->with('success', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listings $listing)
    {
        if ($listing->image) {
            Storage::delete($listing->image);
        }

        $listing->delete();

        // Log the listing deletion activity
        activity()
            ->causedBy(auth()->user())
            ->log('deleted listing');

        return redirect()->route('listings.index')->with('success', 'Listing deleted successfully.');
    }

    /**
     * Store a raffle entry for the specified listing.
     */
    public function storeRaffleEntry(Request $request, $listingId)
    {
        $user = auth()->user();
        $listing = Listings::findOrFail($listingId);
    
        $raffleEntry = RaffleEntry::create([
            'user_id' => $user->id,
            'listing_id' => $listingId,
        ]);
    
        $listing->increment('tickets_sold');

        if ($listing->tickets_sold >= $listing->amount_of_tickets) {
            $this->selectWinner($listingId);
        }
    
        // Log the raffle entry activity
        activity()
            ->causedBy($user)
            ->performedOn($raffleEntry)
            ->log('entered raffle');

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

        return Inertia::render('Listings/RaffleEntriesPage', [
            'raffleEntries' => $raffleEntries,
        ]);
    }

    public function selectWinner($listingId)
    {
        $listing = Listings::with('raffleEntries')->findOrFail($listingId);
    
        if ($listing->tickets_sold >= $listing->amount_of_tickets) {
            $winnerEntry = $listing->raffleEntries->random();
    
            $listing->winner_user_id = $winnerEntry->user_id;
            $listing->is_active = false;
            $listing->save();
    
            foreach ($listing->raffleEntries as $entry) {
                // Send notification to each user (implement notification logic)
            }
    
            // Log the winner selection activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($listing)
                ->withProperties(['winner_id' => $winnerEntry->user_id])
                ->log('selected winner');

            return $winnerEntry->user_id;
        }
    
        return null;
    }
}
