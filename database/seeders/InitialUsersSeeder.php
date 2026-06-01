<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Editor',
                'email' => 'editor@example.com',
                'role' => User::ROLE_EDITOR,
            ],
            [
                'name' => 'Viewer',
                'email' => 'viewer@example.com',
                'role' => User::ROLE_VIEWER,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'dni' => null,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => $userData['role'],
                    'status' => User::STATUS_ACTIVE,
                ]
            );
        }
    }
}
