<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class LocationPricingDecorator extends PriceDecorator
{
    public function __construct(PricingStrategyInterface $strategy)
    {
        parent::__construct($strategy);

    }

    public function calculatePrice(Order $order, $rule=false): float
    {
        $price = $this->pricingStrategy->calculatePrice($order);
        if ($order->location and $order->location->discount > 0.0) {
            $adjustedPrice = $price * (1 - $order->location->discount / 100);
            if ($rule) {
                $adjustmentAmount = $adjustedPrice - $order->final_price;
                $rule = $this->activate($rule);
                $order->applyBuiltRule($rule, $adjustmentAmount);
                $order->save();
            }
            return $adjustedPrice;

        }
        return $price;
    }

    public function getDescription(): string
    {
        return $this->pricingStrategy->getDescription() . " + Location Pricing Rule Applied";
    }
}
