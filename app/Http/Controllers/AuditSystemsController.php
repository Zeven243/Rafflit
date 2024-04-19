<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class AuditSystemsController extends Controller
{
    public function index(Request $request)
    {
        // Assuming you have a method to determine the user's location from their IP
        // This could be a service or a helper function you implement
        $getUserLocation = function ($ipAddress) {
            // Implement logic to determine location from IP address
            // This is a placeholder function
            return 'Location for ' . $ipAddress;
        };

        $audits = Activity::with('causer')->latest()->paginate(20)->through(function ($audit) use ($getUserLocation) {
            // Add IP address and location to the audit object
            // Assuming you have logged these details as custom properties
            $ipAddress = $audit->getExtraProperty('ip_address');
            $location = $getUserLocation($ipAddress);

            // Add the IP address and location to the audit object for easy access in the frontend
            $audit->ip_address = $ipAddress;
            $audit->location = $location;

            return $audit;
        });

        return Inertia::render('AuditSystems/index', [
            'audits' => $audits,
        ]);
    }

    // Example method to log an activity with custom properties
    public function logActivity(Request $request)
    {
        // Log an activity with IP address and other custom properties
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => $request->ip(),
                // Add other custom properties here
            ])
            ->log('Some activity description');

        // Redirect or return response
    }
}