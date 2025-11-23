<?php

namespace App\Repositories;

use App\Contracts\PriceRuleRepositoryInterface;
use App\Models\BuiltinPriceRule;
use App\Models\PriceRule;
use Illuminate\Database\Eloquent\Collection;

class EloquentPriceRuleRepository implements PriceRuleRepositoryInterface
{
    public function all(): Collection
    {
        return PriceRule::all();
    }

    public function findById(int $id): ?PriceRule
    {
        return PriceRule::findOrFail($id);
    }

    public function save(PriceRule $priceRule): PriceRule
    {
        $priceRule->save();
        return $priceRule;
    }

    public function update(PriceRule $priceRule): PriceRule
    {
        $priceRule->save();
        return $priceRule;
    }

    public function delete(int $id): bool
    {
        return PriceRule::destroy($id);
    }

    public function allActiveRules($orderId): Collection
    {
        $activeRules = PriceRule::where('is_active', true)
            ->whereDoesntHave('orders', function ($query) use ($orderId) {
                $query->where('order_id', $orderId);
            })
            ->get();

        if ($activeRules->isEmpty()) {
            $newRule = PriceRule::firstOrCreate(
                [
                    'rule_name' => 'Order quantity count up to 10 pieces',
                    'rule_type' => 'volume',
                    'condition_type' => '>',
                    'entity_id' => 0,
                ],
                [
                    'discount_type' => 'percent',
                    'discount_value' => 3,
                    'condition_value' => '10',
                ]
            );

            $activeRules->push($newRule);
        }

        return $activeRules;
    }

    public function findApplicableRules($options)
    {
        // TODO: Implement findApplicableRules() method.
    }
}
