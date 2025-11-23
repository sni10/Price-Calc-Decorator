<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class CategoryPricingDecorator extends PriceDecorator
{
    public function __construct(PricingStrategyInterface $strategy)
    {
        parent::__construct($strategy);

    }

    public function calculatePrice(Order $order, $rule=false): float
    {
        $price = $this->pricingStrategy->calculatePrice($order);
        if ($order->category and $order->category->discount > 0) {
            $adjustedPrice = $price * (1 - $order->category->discount / 100);
            if ($rule) {
                $adjustmentAmount = $adjustedPrice - $price;
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
        return $this->pricingStrategy->getDescription() . " + Category Pricing Rule Applied";
    }
}
