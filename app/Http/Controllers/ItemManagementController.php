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
        $listings = Listings::with('category')->get();
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

    public function updateStatus(Request $request, Listings $listing)
    {
        $listing->is_active = $request->input('is_active');
        $listing->save();
    
        return response()->json(['success' => true]);
    }
    


    public function updateShippingStatus(Request $request, Listings $listing)
    {
        $listing->shipping_status = $request->input('shipping_status');
        $listing->save();

        if ($listing->shipping_status === 'delivered') {
            $listing->is_active = false;
            $listing->save();
        }

        return redirect()->route('item-management.index')->with('success', 'Shipping status updated successfully.');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('searchQuery');
        $filterFields = $request->input('filterFields', []);

        $query = Listings::query();

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery, $filterFields) {
                foreach ($filterFields as $field => $enabled) {
                    if ($enabled) {
                        if ($field === 'category') {
                            $q->orWhereHas('category', function ($q) use ($searchQuery) {
                                $q->where('name', 'like', "%{$searchQuery}%");
                            });
                        } else {
                            $q->orWhere($field, 'like', "%{$searchQuery}%");
                        }
                    }
                }
            });
        }

        $listings = $query->with('category')->get();
        $categories = Category::all();

        return Inertia::render('ItemManagement/index', [
            'listings' => $listings,
            'categories' => $categories,
        ]);
    }
}
