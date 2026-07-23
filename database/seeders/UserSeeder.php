<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Library Admin',
            'email' => 'admin@library.com',
            'password' => 'password',
            'role' => UserRole::LIBRARIAN,
            'status' => UserStatus::ACTIVE,
            'phone' => '9999999999',
            'address' => 'Library',
        ]);

        User::create([
            'name' => 'John Member',
            'email' => 'member@library.com',
            'password' => 'password',
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
            'phone' => '8888888888',
            'address' => 'Member Address',
        ]);
    }
}