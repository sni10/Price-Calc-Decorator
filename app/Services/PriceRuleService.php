<?php

namespace App\Services;

use App\Contracts\PriceRuleServiceInterface;
use App\Models\Order;

class PriceRuleService implements PriceRuleServiceInterface
{
    protected RuleEngine $ruleEngine;

    public function __construct(RuleEngine $ruleEngine)
    {
        $this->ruleEngine = $ruleEngine;
    }

    public function calculateFinalPrice(Order $order): float
    {
        return $this->ruleEngine->applyRules($order);
    }
}
