<?php

namespace App\Contracts;

use App\Models\PriceRule;
use Illuminate\Database\Eloquent\Collection;

interface PriceRuleRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?PriceRule;

    public function save(PriceRule $priceRule): PriceRule;

    public function update(PriceRule $priceRule): PriceRule;

    public function delete(int $id): bool;

    public function findApplicableRules($options);
}
