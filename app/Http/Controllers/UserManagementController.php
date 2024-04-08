<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::with('roles')->get()->map(function ($user) {
            $user->selected_role = $user->roles->first() ? $user->roles->first()->id : null;
            return $user;
        });
        $roles = Role::all();
    
        return Inertia::render('UserManagement/index', [
            'users' => $users,
            'roles' => $roles,
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
            'role_id' => 'required|exists:roles,id', // Ensure 'role_id' is validated
        ]);
    
        // Update the user
        $user->update($validated);
    
        // Update the user's role in the pivot table
        $user->roles()->sync([$request->role_id]); // Use 'sync' to update the role
    
        // Redirect to the user management index with a success message
        return redirect()->route('user-management.index')->with('success', 'User updated successfully.');
    }
    
    
    
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
    
        // Use a 303 response code for the redirect to ensure compatibility with Inertia.js
        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.')->setStatusCode(303);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);
    
        // Update the user's role in the pivot table
        $user->roles()->sync([$validated['role_id']]);
    
        return redirect()->route('user-management.index')->with('success', 'User role updated successfully.');
    }

}