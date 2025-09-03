<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed link types first (they're referenced by links)
        $this->call(LinkTypeSeeder::class);
        
        // Seed demo data (user, categories, and links)
        $this->call(DemoDataSeeder::class);
    }
}
