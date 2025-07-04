<?php

namespace Database\Seeders;

use App\Models\Stall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StallOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Stall = Stall::create([
            'name' => 'Test Stall',
            'type' => 'others',
            'location'=> 'Other',
        ]);

        $Stall->stallOwners()->create([
            'name' => 'Test Owner',
            'email' => 'stallowner@test.com',
            'password' => 'password',
            'contact_number' => '1234567890',
        ]);
    }
}
