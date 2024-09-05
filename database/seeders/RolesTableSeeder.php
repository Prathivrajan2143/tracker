<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of roles
        $roles = [
            ['name' => 'organization', 'description' => 'Role for the organization level'],
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'manager', 'description' => 'Manager overseeing operations'],
            ['name' => 'team lead', 'description' => 'Team lead responsible for leading a team'],
            ['name' => 'project head', 'description' => 'Head of a specific project'],
            ['name' => 'coder', 'description' => 'Developer or coder role'],
            ['name' => 'auditor', 'description' => 'Role responsible for auditing'],
        ];

        // Insert roles into the table
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
