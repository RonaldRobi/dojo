<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Dojo;
use App\Services\RoleService;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dojo = Dojo::first();
        
        if (!$dojo) {
            $this->command->warn('No dojo found. Please run DojoSeeder first.');
            return;
        }

        $roleService = app(RoleService::class);

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@dojo.com',
                'password' => 'password',
                'role' => 'super_admin',
                'dojo_id' => null, // Super admin tidak terikat dojo
            ],
            [
                'name' => 'Owner Demo',
                'email' => 'owner@dojo.com',
                'password' => 'password',
                'role' => 'owner',
                'dojo_id' => $dojo->id,
            ],
            [
                'name' => 'Finance Demo',
                'email' => 'finance@dojo.com',
                'password' => 'password',
                'role' => 'finance',
                'dojo_id' => $dojo->id,
            ],
            [
                'name' => 'Coach Demo',
                'email' => 'coach@dojo.com',
                'password' => 'password',
                'role' => 'coach',
                'dojo_id' => $dojo->id,
            ],
            [
                'name' => 'Student Demo',
                'email' => 'student@dojo.com',
                'password' => 'password',
                'role' => 'student',
                'dojo_id' => $dojo->id,
            ],
            [
                'name' => 'Parent Demo',
                'email' => 'parent@dojo.com',
                'password' => 'password',
                'role' => 'parent',
                'dojo_id' => $dojo->id,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'dojo_id' => $userData['dojo_id'],
                    'status' => 'active',
                    'password_changed_at' => now(),
                ]
            );

            // Assign role - untuk super admin, kita assign dengan dojo pertama juga untuk konsistensi
            $assignDojoId = $userData['dojo_id'] ?? $dojo->id;
            $roleService->assignRole($user, $userData['role'], $assignDojoId);
        }

        $this->command->info('Demo users created successfully!');
        $this->command->info('All users have password: password');
    }
}

