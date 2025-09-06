<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // cek apakah user sudah ada
        if(!User::where('email', 'admin@domain.com')->exists()) {
            User::create([
                'name' => 'Admin SPPG',
                'email' => 'admin@domain.com',       // email baru
                'password' => Hash::make('Admin123!'), // password baru, aman karena di-hash
                'role' => 'admin',
            ]);
        }
    }
}