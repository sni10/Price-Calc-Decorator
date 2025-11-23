<?php

namespace App\Services;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class PricingContext
{
    private $strategy;

    public function __construct(PricingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getCurrentStrategy(): PricingStrategyInterface
    {
        return $this->strategy;
    }

    public function setStrategy(PricingStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function getPrice(Order $order): float
    {
        return $this->strategy->base_price;
    }

    public function calculatePrice(Order $order, $rule=false): float
    {
        return $this->strategy->calculatePrice($order, $rule);
    }
}

