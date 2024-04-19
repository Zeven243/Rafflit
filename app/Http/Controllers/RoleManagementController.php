<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleManagementController extends Controller
{
    // Display the role management page with existing roles
    public function index()
    {
        $roles = Role::all(); // Fetch all roles from the database

        return Inertia::render('RoleManagement/index', [
            'roles' => $roles,
        ]);
    }

    // Store a new role in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    // Additional methods for role management functionality
    // ...
}
