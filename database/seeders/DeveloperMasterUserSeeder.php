<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DeveloperMasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the "Developer-Master" role if it doesn't exist
        $developerMasterRole = Role::firstOrCreate([
            'name' => 'Developer-Master',
        ]);

        // Create your user
        $user = User::create([
            'first_name' => 'Hein',
            'last_name' => 'Engelbrecht',
            'email' => 'Hein.Engelbrecht@Rafflit.com',
            'password' => Hash::make('Password'),
            'profile_picture' => null, // Set the profile picture path if available
            'company' => 'Rafflit',
        ]);

        // Assign the "Developer-Master" role to your user
        $user->roles()->attach($developerMasterRole);
    }
}
