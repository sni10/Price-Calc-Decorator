<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;

abstract class PriceDecorator implements PricingStrategyInterface
{
    protected PricingStrategyInterface $pricingStrategy;

    public function __construct(PricingStrategyInterface $pricingStrategy)
    {
        $this->pricingStrategy = $pricingStrategy;
    }

    public function activate($rule)
    {
        $rule->is_active = true;
        $rule->save();
        return $rule;
    }
}
