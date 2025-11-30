<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create the Test User (Login Credentials)
        $user = User::create([
            'name' => 'Test Admin',
            'email' => 'test@app.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create a Maintenance Request associated with that user
        MaintenanceRequest::create([
            'user_id' => $user->id,
            'title' => 'Tinker Bypass Test Request',
            'description' => 'This request was created via the Seeder to bypass the tinker error.',
            'location' => 'Seeder Test Location',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        // You can add more requests or users here for mass testing.
    }
}