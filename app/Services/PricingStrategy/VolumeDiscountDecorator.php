<?php

namespace App\Services\PricingStrategy;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;

class VolumeDiscountDecorator extends PriceDecorator
{
    protected float|int $value;
    protected string $type;
    protected float|int $condition_value;
    protected string $condition_type;



    public function __construct(PricingStrategyInterface $strategy, $discount_value, $discount_type, $condition_value, $condition_type)
    {
        parent::__construct($strategy);
        $this->value = $discount_value;
        $this->type = $discount_type;
        $this->condition_value = $condition_value;
        $this->condition_type = $condition_type;
    }

    public function calculatePrice(Order $order, $rule=false): float
    {
        $price = $this->pricingStrategy->calculatePrice($order);
        $conditions = [
            '>=' => fn($quantity, $value) => $quantity >= $value,
            '>'  => fn($quantity, $value) => $quantity > $value,
            '<=' => fn($quantity, $value) => $quantity <= $value,
            '<'  => fn($quantity, $value) => $quantity < $value,
        ];

        // $order->quantity, $order->количесвто покупок больше 100, дней в игре более 250, подарок на 100ю
        // покупку и еще сколько у маркетинга фантазии хватит. Механизм тоже можно будет
        // аккуратно в сервис вынести какой-нибудь. Может трейтом чего докинуть... детали

        if($this->type === 'percent') {
            if (isset($conditions[$this->condition_type]) && $conditions[$this->condition_type]($order->quantity, $this->condition_value)) {
                $adjustedPrice = $price * (1 - $this->value / 100);
                if ($rule) {
                    $adjustmentAmount = $adjustedPrice - $order->final_price;
                    $rule = $this->activate($rule);
                    $order->applyRule($rule, $adjustmentAmount);
                    $order->save();
                }
                return $adjustedPrice;
            }
        }

        return $price;
    }

    public function getDescription(): string
    {
        return $this->pricingStrategy->getDescription() . " + Volume discount for orders over " . $this->condition_value;
    }

    public function getDiscountValue(): float
    {
        return $this->value;
    }

    public function getDiscountType():  string
    {
        return $this->type;
    }


    public function getConditionValue(): float
    {
        return $this->condition_value;
    }

    public function getConditionType():  string
    {
        return $this->condition_type;
    }




}
