<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BuiltinPriceRule;
use Illuminate\Database\Seeder;

class BuiltinPriceRuleSeeder extends Seeder
{
    public function run(): void
    {
        BuiltinPriceRule::insert([
            [
                'rule_name' => 'CategoryPricingDecorator',
                'discount_type' => 'percent',
                'discount_value' => 5.00,
                'is_active' => true,
                'description' => 'Base price calculation + Category Pricing Rule Applied',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rule_name' => 'LocationPricingDecorator',
                'discount_type' => 'percent',
                'discount_value' => 4.00,
                'is_active' => true,
                'description' => 'Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rule_name' => 'SellerDiscountDecorator',
                'discount_type' => 'percent',
                'discount_value' => 11.00,
                'is_active' => true,
                'description' => 'Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied + Seller Discount Rule Applied',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
