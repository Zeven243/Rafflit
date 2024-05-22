<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Illuminate\Http\Request;

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
}
