<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeOfService;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'service_name'  => 'Cuci Komplit (Cuci + Gosok)',
                'price'         => 6000,
                'description'   => 'Layanan cuci bersih dan gosok rapi (per kg)',
            ],
            [
                'service_name'  => 'Hanya Gosok',
                'price'         => 3500,
                'description'   => 'Layanan setrika saja (per kg)',
            ],
            [
                'service_name'  => 'Cuci Kilat (6 Jam)',
                'price'         => 12000,
                'description'   => 'Layanan cuci super cepat selesai dalam 6 jam',
            ],
            [
                'service_name'  => 'Cuci Bedcover Besar',
                'price'         => 25000,
                'description'   => 'Cuci bedcover ukuran King/Queen (per piece)',
            ],
        ];

        foreach ($services as $service) {
            TypeOfService::create($service);
        }
    }
}
