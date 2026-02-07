<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample upcoming events
        Event::create([
            'title' => 'Laravel Conference 2026',
            'starts_at' => Carbon::now()->addDays(14)->setTime(9, 0),
            'capacity' => 500,
            'seats_taken' => 0,
        ]);

        Event::create([
            'title' => 'Web Development Workshop',
            'starts_at' => Carbon::now()->addDays(7)->setTime(14, 0),
            'capacity' => 50,
            'seats_taken' => 0,
        ]);

        // Create additional events
        Event::factory(8)->available()->create();
    }
}
