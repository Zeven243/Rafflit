<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Models\Listing;
use App\Models\Listings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Display a listing of the searches.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searches = Search::latest()->get();

        return view('searches.index', compact('searches'));
    }

    /**
     * Show the form for creating a new search.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('searches.create');
    }

    /**
     * Store a newly created search in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'query' => 'required|max:255',
            // Add other validation rules as needed
        ]);

        $search = Search::create($validatedData);

        return redirect()->route('searches.show', $search->id)
            ->with('success', 'Search created successfully.');
    }

    /**
     * Display the specified search.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $search = Search::findOrFail($id);

        return view('searches.show', compact('search'));
    }

    /**
     * Show the form for editing the specified search.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $search = Search::findOrFail($id);

        return view('searches.edit', compact('search'));
    }

    /**
     * Update the specified search in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'query' => 'required|max:255',
            // Add other validation rules as needed
        ]);

        $search = Search::findOrFail($id);
        $search->update($validatedData);

        return redirect()->route('searches.show', $search->id)
            ->with('success', 'Search updated successfully.');
    }

    /**
     * Remove the specified search from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $search = Search::findOrFail($id);
        $search->delete();

        return redirect()->route('searches.index')
            ->with('success', 'Search deleted successfully.');
    }

    /**
     * Fetch recent searches for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentSearches()
    {
        try {
            $recentSearches = Auth::user()->searches()->latest()->take(5)->pluck('query');
            return response()->json($recentSearches);
        } catch (\Exception $e) {
            Log::error('Error fetching recent searches: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching recent searches'], 500);
        }
    }

    /**
     * Fetch trending searches.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trendingSearches()
    {
        try {
            // Fetch the top 3 most searched listing items
            $trendingSearches = DB::table('searches')
                ->select('query', DB::raw('count(*) as count'))
                ->groupBy('query')
                ->orderByDesc('count')
                ->limit(3)
                ->pluck('query')
                ->toArray();

            return response()->json($trendingSearches);
        } catch (\Exception $e) {
            Log::error('Error fetching trending searches: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching trending searches'], 500);
        }
    }

/**
 * Save a search query for the authenticated user.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function saveSearch(Request $request)
{
    try {
        $request->validate(['query' => 'required|string|min:3']);

        $user = Auth::user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        $query = $request->input('query');

        // Check if the search query is related to an actual item in the listings table
        $listing = Listings::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->first();

        if ($listing) {
            $user->searches()->create(['query' => $query]);
        }

        return response()->json(['message' => 'Search query saved successfully']);
    } catch (\Exception $e) {
        Log::error('Error saving search query: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while saving the search query'], 500);
    }
}
}
