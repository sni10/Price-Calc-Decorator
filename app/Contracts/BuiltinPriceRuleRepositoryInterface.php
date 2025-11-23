<?php

namespace App\Contracts;

use App\Models\BuiltinPriceRule;
use Illuminate\Database\Eloquent\Collection;

interface BuiltinPriceRuleRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?BuiltinPriceRule;

    public function save(BuiltinPriceRule $priceRule): BuiltinPriceRule;

    public function update(BuiltinPriceRule $priceRule): BuiltinPriceRule;

    public function delete(int $id): bool;

    public function findApplicableRules($options);
}
