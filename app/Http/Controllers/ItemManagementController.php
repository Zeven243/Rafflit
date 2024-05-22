<?php

// app/Http/Controllers/ItemManagementController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listings;
use App\Models\Category;
use Inertia\Inertia;

class ItemManagementController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listings::all();
        $categories = Category::all();

        return Inertia::render('ItemManagement/index', [
            'listings' => $listings,
            'categories' => $categories,
        ]);
    }

    public function destroy(Listings $listing)
    {
        $listing->delete();
        return redirect()->route('item-management.index')->with('success', 'Listing deleted successfully.');
    }
}







