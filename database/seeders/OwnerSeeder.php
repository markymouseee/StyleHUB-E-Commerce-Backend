<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Group 1',
            'email' => 'group1@gmail.com',
            'username' => 'group1',
            'password' => 'Owner@Group1',
            'role' => 'owner'
        ]);
    }
}
