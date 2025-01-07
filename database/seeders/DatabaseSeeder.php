<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Speaker::factory(10)->create();
        Talk::factory(10)->create();
        Venue::factory(10)->create()->each(function ($venue) {
            Conference::factory()->create(['region' => $venue->region, 'venue_id' => $venue->id]);
        });

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);
    }
}
