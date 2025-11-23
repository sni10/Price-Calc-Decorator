<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = ['seller_id', 'location_id', 'category_id', 'quantity', 'base_price', 'final_price', 'apply_seller_discount'];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function appliedRules(): BelongsToMany
    {
        return $this->belongsToMany(PriceRule::class, 'order_price_rules')
            ->withPivot('adjustment_amount')
            ->withTimestamps()
            ;
    }

    public function appliedBuiltRules(): BelongsToMany
    {
        return $this->belongsToMany(BuiltinPriceRule::class, 'order_builtin_price_rules')
            ->withPivot('discount_amount')
            ->withTimestamps()
            ;
    }

    public function applyBuiltRule(BuiltinPriceRule $rule, float $adjustmentAmount): void
    {
        $this->appliedBuiltRules()->attach($rule->id, ['discount_amount' => $adjustmentAmount]);
    }

    public function applyRule(PriceRule $rule, float $adjustmentAmount): void
    {
        $this->appliedRules()->attach($rule->id, ['adjustment_amount' => $adjustmentAmount]);
    }

    public function setOptions(array $options): void
    {
        $this->apply_seller_discount = $options['apply_seller_discount'] ?? 0;
        $this->category_id = $options['category_id'] ?? $this->category_id;
        $this->location_id = $options['location_id'] ?? $this->location_id;
        $this->base_price = $options['base_price'] ?? $this->base_price;
        $this->quantity = $options['quantity'] ?? $this->quantity;
        $this->final_price = $this->base_price;
    }
}
