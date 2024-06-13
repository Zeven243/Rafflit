<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => 'nullable|image|max:2048',
            'user_type' => 'required|in:individual,business',
            'phone' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255',
            'selling_preference' => 'required|in:sell,buy',
            'terms_accepted' => 'required|in:1',
            'shipping_address' => 'required|string|max:255',
        ]);
    
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'company' => $request->company,
            'vat_number' => $request->user_type === 'business' ? $request->vat_number : null,
            'selling_preference' => $request->selling_preference,
            'shipping_address' => $request->shipping_address,
            'terms_accepted' => $request->terms_accepted,
        ]);
    
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
    
            // Debugging: Check if the file is valid
            if ($profilePicture->isValid()) {
                $filename = Str::slug($user->first_name) . '_' . time() . '.' . $profilePicture->getClientOriginalExtension();
                $path = $profilePicture->storeAs('public/profile_pictures', $filename);
                $user->profile_picture = $path;
                $user->save();
            } else {
                // Debugging: Log the error
                \Log::error('Profile picture upload failed: Invalid file.');
                return redirect()->back()->withErrors(['profile_picture' => 'Failed to upload the profile picture.']);
            }
        }
    
        // Assign the "Standard User" role to the newly registered user
        $standardUserRole = Role::where('name', 'Standard User')->first();
        if ($standardUserRole) {
            $user->assignRole($standardUserRole);
        }
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect(RouteServiceProvider::HOME);
    }
    
}
