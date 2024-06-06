<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $validatedData['name'], 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $role->syncPermissions($permissions);
        }

        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validatedData['name']]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
