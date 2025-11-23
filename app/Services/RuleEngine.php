<?php

namespace App\Services;

use App\Contracts\PricingStrategyInterface;
use App\Models\Order;
use App\Repositories\EloquentBuiltinPriceRuleRepository;
use App\Repositories\EloquentPriceRuleRepository;
use App\Services\PricingStrategy\BasePriceStrategy;
use App\Services\PricingStrategy\CategoryPricingDecorator;
use App\Services\PricingStrategy\LocationPricingDecorator;
use App\Services\PricingStrategy\SellerDiscountDecorator;
use App\Services\PricingStrategy\VolumeDiscountDecorator;

class RuleEngine
{
    private EloquentBuiltinPriceRuleRepository $builtinPriceRuleRepository;
    private EloquentPriceRuleRepository $ruleRepository;
    private PricingContext $context;

    public function __construct(EloquentPriceRuleRepository $ruleRepository, EloquentBuiltinPriceRuleRepository $builtinPriceRuleRepository, PricingContext $context)
    {
        $this->builtinPriceRuleRepository = $builtinPriceRuleRepository;
        $this->ruleRepository = $ruleRepository;
        $this->context = $context;
    }

    public function applyRules(Order $order): float
    {
        $this->context->setStrategy(new BasePriceStrategy());

        $order = $this->applyBuiltInDiscounts($order);

        $rules = $this->ruleRepository->allActiveRules($order->id);

        foreach ($rules as $rule) {
            if ($this->checkCondition($order, $rule)) {
                $strategy = $this->createStrategyForRule($rule);
                $this->context->setStrategy($strategy);
                $adjustedPrice = $this->context->calculatePrice($order, $rule);
                $rule->description = $strategy->getDescription();
                $this->ruleRepository->save($rule);
                $order->final_price = $adjustedPrice;
            }
        }
        $order->save();
        return $order->final_price;
    }

    private function applyBuiltInDiscounts($order): Order
    {
        $currentStrategy = $this->context->getCurrentStrategy();

        $categoryPricingDecorator = new CategoryPricingDecorator($currentStrategy);
        $locationPricingDecorator = new LocationPricingDecorator($categoryPricingDecorator);
        $sellerDiscountDecorator = new SellerDiscountDecorator($locationPricingDecorator);

        $strategies = [
            $categoryPricingDecorator,
            $locationPricingDecorator,
            $sellerDiscountDecorator,
        ];

        foreach ($strategies as $strategy) {
            $this->context->setStrategy($strategy);
            $discount_value = $this->getDiscountValue($strategy, $order);

            $rule = $this->builtinPriceRuleRepository->activeRule($strategy, $order->id, $discount_value);

            $adjustedPrice = $this->context->calculatePrice($order, $rule);
            $this->builtinPriceRuleRepository->save($rule);
            $order->final_price = $adjustedPrice;
            $order->save();
        }

        return $order;
    }

    private function checkCondition(Order $order, $rule): bool
    {
        return $order->quantity >= (int) $rule->condition;
    }

    private function getDiscountValue($strategy, $order): float
    {
        return match (class_basename($strategy::class)) {
            'SellerDiscountDecorator' => $order->seller->personal_discount,
            'LocationPricingDecorator' => $order->location->discount,
            'CategoryPricingDecorator' => $order->category->discount,
            default => 0.0
        };
    }

    private function createStrategyForRule($rule): PricingStrategyInterface
    {
        $currentStrategy = $this->context->getCurrentStrategy();
        return match ($rule->rule_type) {
            'volume' => new VolumeDiscountDecorator($currentStrategy, $rule->discount_value,  $rule->discount_type, $rule->condition_value,  $rule->condition_type),
            default => $currentStrategy,
        };
    }
}
