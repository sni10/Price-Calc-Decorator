<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceRule extends Model
{
    protected $fillable = [
        'rule_type',
        'rule_name',
        'entity_id',
        'discount_type',
        'discount_value',
        'condition_type',
        'condition_value',
        'description'
    ];


    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_price_rules', 'price_rule_id', 'order_id')
            ->withPivot('adjustment_amount')
            ->withTimestamps();
    }

}
