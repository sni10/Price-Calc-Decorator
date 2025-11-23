<?php

namespace App\Contracts;

use App\Models\Order;

interface PricingStrategyInterface
{
    public function calculatePrice(Order $order, $rule=false): float;
    public function getDescription(): string;
}
