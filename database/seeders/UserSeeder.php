<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
        'id_level'   => 1, // Administrator
        'name'       => 'Admin',
        'email'      => 'admin@gmail.com',
        'password'   => bcrypt('12345678'),
    ]);
    }
}
