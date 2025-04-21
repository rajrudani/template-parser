<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\School;
use App\Models\SchoolEvent;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolEvent::create([
            'school_id'  => 1,
            'event_name' => 'Science Exhibition',
            'event_date' => Carbon::parse('2025-07-15'),
            'venue'      => 'Main Hall, ABC School',
            'time'       => '10:30 AM',
            'subject'    => 'Innovations in Science',
        ]);

        SchoolEvent::create([
            'school_id'  => 1,
            'event_name' => 'Independence Day Celebration',
            'event_date' => Carbon::parse('2025-08-15'),
            'venue'      => 'School Grounds',
            'time'       => '08:00 AM',
            'subject'    => '75th Independence Day',
        ]);

        SchoolEvent::create([
            'school_id'  => 2,
            'event_name' => 'Science Exhibition',
            'event_date' => Carbon::parse('2025-07-10'),
            'venue'      => 'Multipurpose Hall',
            'time'       => '10:30 AM',
            'subject'    => 'Innovations & Projects by Students',
        ]);
        
        SchoolEvent::create([
            'school_id'  => 2,
            'event_name' => 'Sports Day',
            'event_date' => Carbon::parse('2025-12-05'),
            'venue'      => 'Main Playground',
            'time'       => '09:00 AM',
            'subject'    => 'Annual Sports Meet 2025',
        ]);
    }
}
