<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Listings;
use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Intervention\Image\Drivers\Gd\Driver;

class ListingsController extends Controller
{
    // Fetch all listings for the dashboard
    public function index(Request $request)
    {
        $listings = Listings::all();
        $categories = Category::all();

        // Fetch potential tickets and existence in cart for each listing
        foreach ($listings as $listing) {
            $listing->potential_tickets = $this->getPotentialTickets($listing->id);
            $listing->exists_in_cart = $this->existsInCart($listing->id);
        }

        return Inertia::render('Dashboard', ['listings' => $listings, 'categories' => $categories]);
    }

    // Get the number of potential tickets for a listing
    private function getPotentialTickets($listingId)
    {
        // Calculate the number of potential tickets for the listing
        $potentialTickets = CartItem::where('listing_id', $listingId)->sum('quantity');

        return $potentialTickets;
    }

    // Check if a listing exists in any user's cart
    private function existsInCart($listingId)
    {
        // Check if the listing exists in any user's cart
        $existsInCart = CartItem::where('listing_id', $listingId)->exists();

        return $existsInCart;
    }


    // Fetch only user's listings for the My Listings page
    public function userIndex(Request $request)
    {
        $userListings = $request->user()->listings()->with('category')->get();
        return Inertia::render('Listings/index', ['listings' => $userListings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return Inertia::render('Listings/create', [
            'categories' => $categories,
            'users' => $users,
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
            'category_id' => 'required|exists:categories,id',
            'company' => 'required|string|max:255',
            'full_price' => 'required|numeric|min:0.01',
            'amount_of_tickets' => 'required|integer|min:1',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/listings', $imageName);
            $imagePath = 'listings/' . $imageName;
        }

        $listing = Listings::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
            'company' => $request->company,
            'full_price' => (float) $request->full_price,
            'amount_of_tickets' => (int) $request->amount_of_tickets,
            'ticket_price' => (float) ($request->full_price / $request->amount_of_tickets),
            'image_path' => $imagePath,
        ]);

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
        $listing->load('category');
        $listing->exists_in_cart = $this->existsInCart($listing->id);
    
        return Inertia::render('Listings/show', [
            'listing' => array_merge($listing->toArray(), ['tickets_sold' => $listing->tickets_sold, 'exists_in_cart' => $listing->exists_in_cart]),
        ]);
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listings $listing)
    {
        $categories = Category::all();
        return Inertia::render('Listings/edit', [
            'listing' => $listing,
            'categories' => $categories,
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
            'category_id' => 'required|exists:categories,id',
            'full_price' => 'required|numeric',
            'amount_of_tickets' => 'required|integer|min:1',
            'image' => 'nullable|image',
        ]);

        $imagePath = $listing->image_path;
        if ($request->hasFile('image')) {
            if ($listing->image_path) {
                Storage::delete($listing->image_path);
            }
            $imagePath = $request->file('image')->store('public/images');
        }

        $listing->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'full_price' => $request->full_price,
            'amount_of_tickets' => $request->amount_of_tickets,
            'ticket_price' => $request->full_price / $request->amount_of_tickets,
            'image_path' => $imagePath,
        ]);

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
        if ($listing->image_path) {
            Storage::delete($listing->image_path);
        }

        $listing->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('deleted listing');

        return redirect()->route('listings.index')->with('success', 'Listing deleted successfully.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Upload the image for a listing.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string  $directory
     * @return string
     */
    protected function uploadImage($image, $directory)
    {
        if ($image) {
            $manager = new ImageManager(new Driver());
            $imageData = $manager->read($image);
            $imageData->resize(300, 200);
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = "public/{$directory}/" . $filename;
            $imageData->save(storage_path('app/' . $path));
            return $path;
        }

        return null;
    }

    public function buyOut(Request $request, $listingId)
    {
        DB::transaction(function () use ($listingId, $request) {
            $listing = Listings::lockForUpdate()->findOrFail($listingId);
        
            if ($listing->tickets_sold === 0) {
                $listing->tickets_sold = $listing->amount_of_tickets;
                $listing->winner_user_id = $request->user()->id;
                $listing->is_active = false;
                $listing->save();
        
                // Create a new RaffleEntry for the user who bought out the item
                RaffleEntry::create([
                    'user_id' => $request->user()->id,
                    'listing_id' => $listingId,
                ]);
        
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($listing)
                    ->log('bought out all tickets');
            } else {
                return redirect()->route('dashboard')->with('error', 'Tickets have already been sold.');
            }
        });
        
        // Return the updated listing data
        return redirect()->route('listings.show', $listingId)->with('success', 'You have successfully bought out all tickets.');
    }
}
