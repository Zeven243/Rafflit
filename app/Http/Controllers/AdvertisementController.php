<?php

// app/Http/Controllers/AdvertisementController.php

namespace App\Http\Controllers;

use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::all();
        return response()->json($advertisements);
    }
}
