<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = Team::create([
            'name' => 'Test Team',
            'college_name' => 'Test College'
        ]);

        $team->participants()->create([
            'name' => 'Test Participant',
            'email' => 'participant@test.com',
            'password' => 'password',
            'contact_number' => '1234567890',
        ]);
    }
}
