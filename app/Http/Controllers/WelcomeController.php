<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Listings;
use App\Models\Category;
use App\Models\CarouselImage;

class WelcomeController extends Controller
{
    public function index()
    {
        $listings = Listings::all();
        $categories = Category::all();
        $carouselImages = CarouselImage::all();

        return Inertia::render('Welcome', [
            'listings' => $listings,
            'categories' => $categories,
            'carouselImages' => $carouselImages,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Welcome $welcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Welcome $welcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Welcome $welcome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Welcome $welcome)
    {
        //
    }
}
