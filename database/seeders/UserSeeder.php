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
        $users = [
            [
                'id_level'   => 1, // Administrator
                'name'       => 'Admin',
                'email'      => 'admin@gmail.com',
                'password'   => bcrypt('12345678'),
            ],
            [
                'id_level'   => 2, // Operator
                'name'       => 'Operator',
                'email'      => 'operator@gmail.com',
                'password'   => bcrypt('12345678'),
            ],
            [
                'id_level'   => 3, // Pimpinan
                'name'       => 'Pimpinan',
                'email'      => 'pimpinan@gmail.com',
                'password'   => bcrypt('12345678'),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
