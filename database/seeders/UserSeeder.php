<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        User::updateOrCreate(

            ['email' => 'admin@bank.ua'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN->value,
            ]

        );

        User::updateOrCreate(

            ['email' => 'employee@bank.ua'],
            [
                'name' => 'Олена Коваль',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => UserRole::EMPLOYEE->value,
            ]

        );
    }
}
