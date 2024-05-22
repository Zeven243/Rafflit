<?php

// app/Http/Controllers/CarouselImageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarouselImage;
use Illuminate\Support\Facades\Storage;

class CarouselImageController extends Controller
{
    /**
     * Display a listing of the carousel images.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CarouselImage::all();
    }

    /**
     * Store a newly uploaded image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('carousel_images', 'public');
                $imagePaths[] = $path;

                CarouselImage::create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('item-management.index')->with('success', 'Images uploaded successfully.');
    }

    /**
     * Remove the specified image from storage.
     *
     * @param  \App\Models\CarouselImage  $carouselImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarouselImage $carouselImage)
    {
        // Delete the image file from storage
        Storage::disk('public')->delete($carouselImage->image_path);

        // Delete the image record from the database
        $carouselImage->delete();

        return response()->json(['message' => 'Image deleted successfully.']);
    }

    /**
     * Replace the specified image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarouselImage  $carouselImage
     * @return \Illuminate\Http\Response
     */
    public function replace(Request $request, CarouselImage $carouselImage)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Delete the old image file from storage
        Storage::disk('public')->delete($carouselImage->image_path);

        // Store the new image file
        $path = $request->file('image')->store('carousel_images', 'public');

        // Update the image record in the database
        $carouselImage->update(['image_path' => $path]);

        return response()->json(['message' => 'Image replaced successfully.']);
    }
}

