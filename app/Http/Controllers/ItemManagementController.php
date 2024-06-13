<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Listings;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use Carbon\Carbon;

class ItemManagementController extends Controller
{
    public function index(Request $request)
    {
        $listings = Listings::with('category')->get();
        $categories = Category::all();
        $advertisements = Advertisement::all();

        // Update shipping status based on delivery confirmation
        foreach ($listings as $listing) {
            $this->updateShippingStatusAutomatically($listing);
        }

        return Inertia::render('ItemManagement/index', [
            'listings' => $listings,
            'categories' => $categories,
            'advertisements' => $advertisements,
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

    public function updateShippingStatusAutomatically(Listings $listing)
    {
        if ($listing->delivery_confirmed) {
            return $listing->shipping_status = 'Delivered';
        } else {
            $sevenDaysAgo = Carbon::now()->subDays(7);
            if ($listing->updated_at < $sevenDaysAgo) {
                return $listing->shipping_status = 'User Confirmation Failed';
            } else {
               return $listing->shipping_status = 'Pending';
            }
        }
        $listing->save();
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

    public function uploadAdvertisement(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'required|url',
        ]);

        $imagePath = $request->file('image')->store('advertisements', 'public');
        $url = $request->input('url');

        // Save the advertisement data to the database
        Advertisement::create([
            'image_path' => $imagePath,
            'url' => $url,
        ]);

        return redirect()->route('item-management.index')->with('success', 'Advertisement uploaded successfully.');
    }

    public function destroyAdvertisement(Advertisement $advertisement)
    {
        $advertisement->delete();
        return response()->json(['success' => true]);
    }

    public function replaceAdvertisement(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'required|url',
        ]);

        $imagePath = $request->file('image')->store('advertisements', 'public');
        $url = $request->input('url');

        $advertisement->update([
            'image_path' => $imagePath,
            'url' => $url,
        ]);

        return response()->json(['success' => true]);
    }
}