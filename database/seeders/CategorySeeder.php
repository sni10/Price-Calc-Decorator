<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Motors',
                'discount' => 5.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wheels',
                'discount' => 8.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
