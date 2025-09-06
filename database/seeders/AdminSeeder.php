<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // <- ini yang kurang

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if(!User::where('email', 'AdminFatimah@sppg.com')->exists()) {
            User::create([
                'name' => 'Admin SPPG',
                'email' => 'AdminFatimah@sppg.com',
                'password' => Hash::make('Fatimah123!'), // password aman
                'role' => 'admin',
            ]);
        }
    }
}