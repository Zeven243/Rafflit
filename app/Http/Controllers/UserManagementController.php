<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // Fetch all users with their roles. Consider using pagination for large datasets.
        $users = User::with('roles')->get();
    
        return Inertia::render('UserManagement/index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('UserManagement/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Add other validation rules as needed
        ]);

        // Create the user
        $user = User::create($validated);

        // Redirect to the user management index with a success message
        return redirect()->route('user-management.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        return Inertia::render('UserManagement/show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        return Inertia::render('UserManagement/edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Add other validation rules as needed
        ]);

        // Update the user
        $user->update($validated);

        // Redirect to the user management index with a success message
        return redirect()->route('user-management.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        // Redirect to the user management index with a success message
        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.');
    }
}