<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::insert([
            [
                'name' => 'Calabria',
                'discount' => 4.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sicily',
                'discount' => 15.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
