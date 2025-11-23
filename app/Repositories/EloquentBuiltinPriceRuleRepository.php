<?php

namespace App\Repositories;

use App\Contracts\BuiltinPriceRuleRepositoryInterface;
use App\Models\BuiltinPriceRule;
use App\Models\PriceRule;
use Illuminate\Database\Eloquent\Collection;

class EloquentBuiltinPriceRuleRepository implements BuiltinPriceRuleRepositoryInterface
{
    public function all(): Collection
    {
        return BuiltinPriceRule::all();
    }

    public function findById(int $id): ?BuiltinPriceRule
    {
        return BuiltinPriceRule::findOrFail($id);
    }

    public function save(BuiltinPriceRule $priceRule): BuiltinPriceRule
    {
        $priceRule->save();
        return $priceRule;
    }

    public function update(BuiltinPriceRule $priceRule): BuiltinPriceRule
    {
        $priceRule->save();
        return $priceRule;
    }

    public function delete(int $id): bool
    {
        return BuiltinPriceRule::destroy($id);
    }


    public function activeRule($strategy, $orderId, $discount_value): ?BuiltinPriceRule
    {
        $ruleName = class_basename($strategy::class);
        $activeRule = BuiltinPriceRule::where('is_active', true)
            ->where('rule_name', $ruleName)
            ->where('discount_value', $discount_value)
            ->whereDoesntHave('orders', function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeRule === null ) {
            return BuiltinPriceRule::firstOrCreate(
                [
                    'rule_name' => $ruleName,
                ],
                [
                    'discount_type' => 'percent',
                    'discount_value' => $discount_value,
                    'description' => $strategy->getDescription(),
                ]
            );
        }

        return $activeRule;
    }

    public function findApplicableRules($options)
    {
        // TODO: Implement findApplicableRules() method.
    }
}
