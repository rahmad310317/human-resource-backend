<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\Company;
use App\Models\Employees;
use App\Models\Responsibility;
use Carbon\Factory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(2)->unverified()->create();
        // Company::factory(10)->create();
        // Team::factory(30)->create();
        // Role::factory(30)->create();
        // Responsibility::factory(30)->create();
        // Employees::factory(30)->create();



        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


    }
}
