<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Developer-Master', 'description' => 'Can do everything in the app.']);
        Role::create(['name' => 'Administrator', 'description' => 'Can do administrative things.']);
        Role::create(['name' => 'Standard User', 'description' => 'Can only create and view their listings.']);
    }
}
