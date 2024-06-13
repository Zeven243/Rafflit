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

        $sellerRole = Role::firstOrCreate(['name' => 'Seller']);
        $sellerRole->givePermissionTo([
            'view dashboard',
            'view listings',
            'create listings',
            'edit listings',
            'delete listings',
            'view profile',
            'edit profile',
            'delete profile',
            'update profile picture',
            'view categories',
        ]);

        $buyerRole = Role::firstOrCreate(['name' => 'Buyer']);
        $buyerRole->givePermissionTo([
            'view dashboard',
            'view listings',
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
            ['email' => 'Heinrich@Rafflit.co.za'],
            [
                'first_name' => 'Heinrich',
                'last_name' => 'Engelbrecht',
                'password' => Hash::make('Password'),
                'profile_picture' => null,
                'user_type' => 'business',
                'company' => 'Rafflit',
                'phone' => '069 720 8308',
                'vat_number' => null,
                'selling_preference' => 'sell',
                'shipping_address' => '51 Killarney Street, Oakdale, Bellville, Cape Town, 7530',
            ]
        );

        // Assign the "Developer-Master" role to the user
        $user->assignRole('Developer-Master');

        // Assign permissions directly to the user
        $user->givePermissionTo(Permission::all());

        // Create Johan user and assign the "Administrator" role
        $johanUser = User::firstOrCreate(
            ['email' => 'Johan@Rafflit.co.za'],
            [
                'first_name' => 'Johan',
                'last_name' => 'Steyn',
                'password' => Hash::make('Password'),
                'profile_picture' => null,
                'user_type' => 'business',
                'phone' => '069 720 8308',
                'company' => 'Rafflit',
                'vat_number' => null,
                'selling_preference' => 'sell',
                'shipping_address' => '51 Killarney Street, Oakdale, Bellville, Cape Town, 7530',
            ]
        );

        $johanUser->assignRole('Administrator');

        // Create Rene user and assign the "Administrator" role
        $reneUser = User::firstOrCreate(
            ['email' => 'Admin@Rafflit.co.za'],
            [
                'first_name' => 'Rene',
                'last_name' => 'Tait-Steyn',
                'password' => Hash::make('Password'),
                'profile_picture' => null,
                'user_type' => 'business',
                'phone' => '069 720 8308',
                'company' => 'Rafflit',
                'vat_number' => null,
                'selling_preference' => 'sell',
                'shipping_address' => '51 Killarney Street, Oakdale, Bellville, Cape Town, 7530',
            ]
        );

        $reneUser->assignRole('Administrator');

        // Reset the permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
