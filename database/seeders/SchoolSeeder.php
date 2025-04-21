<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::create([
            'name'           => 'Delhi Public School',    
            'address'        => 'Vadodara, Gujarat.',
            'affiliation_no' => '87945632',
            'school_code'    => 'D052VG',
            'email'          => 'dpsvadodara@example.com',
            'contact'        => '8759653655',
            'website'        => 'www.dps.com',
            'principal_id'   => 1,
        ]);

        School::create([
            'name'           => 'Javahar Navodaya Vidhyalaya',    
            'address'        => 'Vadodara, Gujarat.',
            'affiliation_no' => '27945632',
            'school_code'    => 'J052VG',
            'email'          => 'jnv@example.com',
            'contact'        => '9959653655',
            'website'        => 'www.jnv.com',
            'principal_id'   => 2,
        ]);
    }
}
