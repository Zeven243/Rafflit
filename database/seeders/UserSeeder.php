<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'User1',
            'last_name' => 'Test',
            'email' => 'User@test.com',
            'password' => Hash::make('Password'),
            'profile_picture' => null, // Set the profile picture path if available
            'company' => 'Rafflit',
        ]);

        $user = User::create([
            'first_name' => 'User2',
            'last_name' => 'Test',
            'email' => 'User@test2.com',
            'password' => Hash::make('Password'),
            'profile_picture' => null, // Set the profile picture path if available
            'company' => 'Rafflit',
        ]);
    }
}
