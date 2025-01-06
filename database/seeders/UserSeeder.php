<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Us Ser',
            'email' => 'user@example.com',
            'password' => Hash::make('pass'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Pak Akar',
            'email' => 'pakar@example.com',
            'password' => Hash::make('pass'),
            'role' => 'pakar',
        ]);
    }
}
