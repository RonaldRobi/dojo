<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin (Head Office)',
                'description' => 'Head Office - Full access to all dojos/branches and system settings',
            ],
            [
                'name' => 'owner',
                'display_name' => 'Dojo Owner (Branch Manager)',
                'description' => 'Branch Manager/Dojo Owner with full operational control of their branch',
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance',
                'description' => 'Finance manager responsible for billing and payments',
            ],
            [
                'name' => 'coach',
                'display_name' => 'Coach/Instructor',
                'description' => 'Martial arts instructor/coach',
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Martial arts student',
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Parent of a student',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
