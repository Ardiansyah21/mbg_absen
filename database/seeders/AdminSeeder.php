<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Farhan',
                'email' => 'farhan@sppg.com',
                'password' => Hash::make('Farhan123!'),
                'role' => 'admin',
            ],
            [
                'name' => 'Fatimah',
                'email' => 'fatimah@sppg.com',
                'password' => Hash::make('Fatimah123!'),
                'role' => 'admin',
            ],
            [
                'name' => 'Nazril',
                'email' => 'nazril@sppg.com',
                'password' => Hash::make('Nazril123!'),
                'role' => 'admin',
            ],
        ];

        foreach ($admins as $admin) {
            if (!User::where('email', $admin['email'])->exists()) {
                User::create($admin);
            }
        }
    }
}