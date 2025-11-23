<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PriceRule;
use Illuminate\Database\Seeder;

class PriceRuleSeeder extends Seeder
{
    public function run(): void
    {
        PriceRule::insert([
            [
                'rule_type' => 'volume',
                'rule_name' => 'Order quantity count up to 10 pieces',
                'is_active' => true,
                'entity_id' => 0,
                'discount_type' => 'percent',
                'discount_value' => 3.00,
                'condition_type' => '>',
                'condition_value' => '10',
                'description' => 'Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied + Seller Discount Rule Applied + Volume discount for orders over 10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
