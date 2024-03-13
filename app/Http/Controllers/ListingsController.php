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
        Listings::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'amount_of_tickets' => $request->amount_of_tickets, // Store the new field
            'user_id' => auth()->user()->id,
            'image' => url($imagePath), // Store the full image path
        ]);
    
        return redirect()->route('listings.index')->with('success', 'Listing created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Listings $listing)
    {
        return Inertia::render('Listings/show', [
            'listing' => $listing->toArray(), // Make sure the image path is included in the listing details
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
            // 'image' => 'image', // Include this if you allow updating the image
        ]);

        // If you allow updating the image, you should handle the image upload here
        // similar to the store method and update the 'image' attribute accordingly.

        $listing->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            // 'image' => $newImagePath, // Update this if you're handling image upload
        ]);

        return redirect()->route('listings.index')->with('success', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listings $listing)
    {
        // If you store images, you may want to delete the image file as well
        if ($listing->image) {
            Storage::delete($listing->image);
        }

        $listing->delete();

        return redirect()->route('listings.index')->with('success', 'Listing deleted successfully.');
    }

    /**
     * Store a raffle entry for the specified listing.
     */

    // Inside ListingsController.php
    public function storeRaffleEntry(Request $request, $listingId)
    {
        $user = auth()->user();
        $listing = Listings::findOrFail($listingId);
    
        // Create a new raffle entry for the user and listing
        RaffleEntry::create([
            'user_id' => $user->id,
            'listing_id' => $listingId,
        ]);
    
    // Increment the tickets_sold value
    $listing->increment('tickets_sold');

    // Check if the raffle is full and select a winner
    if ($listing->tickets_sold >= $listing->amount_of_tickets) {
        $this->selectWinner($listingId);
    }
    
        // Return a response, possibly redirecting to the dashboard or returning JSON data
        return redirect()->route('dashboard')->with('success', 'Entered raffle successfully.');
    }

    public function getRaffleEntries()
    {
        $user = auth()->user();
        // Eager load the 'listing' relationship with each raffle entry
        $raffleEntries = $user->raffleEntries()->with('listing')->get();
        $allListings = Listings::all(); // Retrieve all listings
    
        return Inertia::render('Dashboard', [
            'raffleEntries' => $raffleEntries,
            'allListings' => $allListings, // Pass all listings to the view
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
    
        // Check if the raffle is full
        if ($listing->tickets_sold >= $listing->amount_of_tickets) {
            // Randomly select a winner
            $winnerEntry = $listing->raffleEntries->random();
    
            // Update the listing with the winner's user ID and set is_active to false
            $listing->winner_user_id = $winnerEntry->user_id;
            $listing->is_active = false;
            $listing->save();
    
            // Notify all users about the result
            foreach ($listing->raffleEntries as $entry) {
                // Send notification to each user (implement notification logic)
            }
    
            return $winnerEntry->user_id; // Return the winner's user ID
        }
    
        return null; // No winner if the raffle is not full
    }

    
}