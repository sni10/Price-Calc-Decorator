<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class SellerDiscountDecorator extends PriceDecorator
{
    public function __construct(PricingStrategyInterface $strategy)
    {
        parent::__construct($strategy);

    }

    public function calculatePrice(Order $order, $rule=false): float
    {
        $price = $this->pricingStrategy->calculatePrice($order);
        if ( $order->apply_seller_discount and $order->seller and $order->seller->personal_discount > 0.0 ) {
            $adjustedPrice = $price * (1 - $order->seller->personal_discount / 100);
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
        return $this->pricingStrategy->getDescription() . " + Seller Discount Rule Applied";
    }
}
