<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class BasePriceStrategy implements PricingStrategyInterface
{
    public function calculatePrice(Order $order, $rule=false): float
    {
        return $order->base_price;
    }

    public function getDescription(): string
    {
        return "Base price calculation";
    }
}
