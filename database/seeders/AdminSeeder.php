<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@ogrenci.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
    }
}
