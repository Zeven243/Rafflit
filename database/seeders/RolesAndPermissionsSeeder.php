<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions based on your routes
        $permissions = [
            'view dashboard',
            'view listings',
            'create listings',
            'edit listings',
            'delete listings',
            'view raffle entries',
            'create raffle entries',
            'view profile',
            'edit profile',
            'delete profile',
            'update profile picture',
            'view categories',
            'create categories',
            'buy out listings',
            'view cart',
            'add to cart',
            'remove from cart',
            'checkout cart',
            'view potential tickets',
            'view audit systems',
            'manage roles',
            'manage items',
            'manage carousel images',
            'manage users',
            'update user roles',
            'delete users',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $developerMasterRole = Role::firstOrCreate(['name' => 'Developer-Master']);
        $developerMasterRole->givePermissionTo(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $adminRole->givePermissionTo([
            'view dashboard',
            'view listings',
            'create listings',
            'edit listings',
            'delete listings',
            'view raffle entries',
            'create raffle entries',
            'view profile',
            'edit profile',
            'delete profile',
            'update profile picture',
            'view categories',
            'create categories',
            'buy out listings',
            'view cart',
            'add to cart',
            'remove from cart',
            'checkout cart',
            'view potential tickets',
            'view audit systems',
            'manage roles',
            'manage items',
            'manage carousel images',
        ]);

        $standardUserRole = Role::firstOrCreate(['name' => 'Standard User']);
        $standardUserRole->givePermissionTo([
            'view dashboard',
            'view listings',
            'create listings',
            'edit listings',
            'delete listings',
            'view raffle entries',
            'create raffle entries',
            'view profile',
            'edit profile',
            'delete profile',
            'update profile picture',
            'view categories',
            'buy out listings',
            'view cart',
            'add to cart',
            'remove from cart',
            'checkout cart',
            'view potential tickets',
        ]);

        // Create your user and assign the "Developer-Master" role
        $user = User::firstOrCreate(
            ['email' => 'Hein.Engelbrecht@Rafflit.com'],
            [
                'first_name' => 'Hein',
                'last_name' => 'Engelbrecht',
                'password' => Hash::make('Password'),
                'profile_picture' => null, // Set the profile picture path if available
                'company' => 'Rafflit',
            ]
        );

        // Assign the "Developer-Master" role to the user
        $user->assignRole('Developer-Master');

        // Assign permissions directly to the user
        $user->givePermissionTo(Permission::all());

        // Reset the permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
