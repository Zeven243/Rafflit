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
            'company' => 'required_if:user_type,business|string|max:255',
            'vat_number' => 'required_if:user_type,business|string|max:255',
            'selling_preference' => 'required|in:sell,buy',
            'terms_accepted' => 'required|accepted',
            'shipping_address' => 'required|string|max:255',
        ]);

        $company = $request->user_type === 'individual' ? $request->first_name . ' ' . $request->last_name : $request->company;

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'company' => $company,
            'vat_number' => $request->user_type === 'business' ? $request->vat_number : null,
            'selling_preference' => $request->selling_preference,
            'shipping_address' => $request->shipping_address,
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $filename = Str::slug($user->first_name) . '_' . time() . '.' . $profilePicture->getClientOriginalExtension();
            $path = $profilePicture->storeAs('public/profile_pictures', $filename);
            $user->profile_picture = $path;
            $user->save();
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
