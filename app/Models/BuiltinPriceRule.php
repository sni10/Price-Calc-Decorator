<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BuiltinPriceRule extends Model
{
    protected $fillable = [
        'rule_name',
        'discount_type',
        'discount_value',
        'discount_amount',
        'description'
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_builtin_price_rules', 'builtin_price_rule_id', 'order_id')
            ->withPivot('discount_amount')
            ->withTimestamps();
    }


}
