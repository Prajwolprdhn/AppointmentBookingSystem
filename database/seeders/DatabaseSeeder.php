<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password'=> 'admin@admin.com',
            'role' => 0,
            'status'=> 1
        ]);
        $departmentNames = [
            'Cardiologists',
            'Neurologists',
            'Pediatricians',
            'Oncologists',
            'Dermatologists',
        ];

        foreach ($departmentNames as $name) {
            Department::create([
                'departments' => $name,
            ]);
        }
    }
}
