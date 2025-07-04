<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Seeder;


class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visitor::create(attributes: [
            'name' => 'Test Visitor',
            'email' => 'visitor@test.com',
            'contact_number' => '9875432452',
            'address' => '123 Visitor Lane',
            'password' => 'password',
        ]);
    }
}
