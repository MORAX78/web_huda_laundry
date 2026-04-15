<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::insert([
        ['level_name' => 'Administrator'],
        ['level_name' => 'Operator'],
        ['level_name' => 'Pimpinan'],
    ]);
    }
}
