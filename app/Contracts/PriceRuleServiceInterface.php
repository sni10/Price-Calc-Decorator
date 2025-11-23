<?php

namespace App\Contracts;

use App\Models\Order;

interface PriceRuleServiceInterface
{

    public function calculateFinalPrice(Order $order): float;

}
