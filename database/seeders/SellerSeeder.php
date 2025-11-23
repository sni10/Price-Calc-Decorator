<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        Seller::insert([
            [
                'name' => 'Test Seller',
                'personal_discount' => 11.00,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
