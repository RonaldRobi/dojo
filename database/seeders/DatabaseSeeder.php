<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        
        // Core seeders (run first)
        $this->command->info('Seeding roles...');
        $this->call([RoleSeeder::class]);
        
        $this->command->info('Seeding dojos...');
        $this->call([DojoSeeder::class]);
        
        $this->command->info('Seeding system settings...');
        $this->call([SystemSettingSeeder::class]);
        
        $this->command->info('Seeding master data (styles, levels, belts)...');
        $this->call([MasterDataSeeder::class]);
        
        $this->command->info('Seeding ranks and requirements...');
        $this->call([RankSeeder::class]);
        
        $this->command->info('Seeding curriculum...');
        $this->call([CurriculumSeeder::class]);
        
        // User and role assignment
        $this->command->info('Seeding users...');
        $this->call([UserSeeder::class]);
        
        // Instructor data
        $this->command->info('Seeding instructors and certifications...');
        $this->call([InstructorSeeder::class]);
        
        // Membership plans
        $this->command->info('Seeding membership plans...');
        $this->call([MembershipSeeder::class]);
        
        // Members and their data
        $this->command->info('Seeding members, ranks, and memberships...');
        $this->call([MemberSeeder::class]);
        
        // Classes and schedules
        $this->command->info('Seeding classes, schedules, and enrollments...');
        $this->call([ClassSeeder::class]);
        
        // Events
        $this->command->info('Seeding events, registrations, and certificates...');
        $this->call([EventSeeder::class]);
        
        // Finance
        $this->command->info('Seeding invoices and payments...');
        $this->call([FinanceSeeder::class]);
        
        // Attendance
        $this->command->info('Seeding attendance records...');
        $this->call([AttendanceSeeder::class]);
        
        $this->command->info('Database seeding completed successfully!');
    }
}
