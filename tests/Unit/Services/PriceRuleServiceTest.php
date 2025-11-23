<?php

namespace Tests\Unit\Services;

use App\Contracts\PricingStrategyInterface;
use App\Models\BuiltinPriceRule;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\Seller;
use App\Repositories\EloquentBuiltinPriceRuleRepository;
use App\Repositories\EloquentPriceRuleRepository;
use App\Services\PriceRuleService;
use App\Services\PricingContext;
use App\Services\PricingStrategy\CategoryPricingDecorator;
use App\Services\PricingStrategy\LocationPricingDecorator;
use App\Services\PricingStrategy\SellerDiscountDecorator;
use App\Services\RuleEngine;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class PriceRuleServiceTest extends TestCase
{

    public function testCalculateFinalPriceWithLocationAndCategory()
    {
        $category = new Category(['name' => 'Electronics', 'discount' => 10]);
        $location = new Location(['name' => 'New York', 'discount' => 5]);

        $order = Mockery::mock(Order::class);

        $seller = Mockery::mock(Seller::class);
        $seller->shouldReceive('getAttribute')->with('personal_discount')->andReturn(3);

        $order->shouldReceive('getAttribute')->with('category')->andReturn($category);
        $order->shouldReceive('getAttribute')->with('location')->andReturn($location);
        $order->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $order->shouldReceive('getAttribute')->with('seller')->andReturn($seller);
        $order->shouldReceive('getAttribute')->with('apply_seller_discount')->andReturn(1);
        $order->shouldReceive('getAttribute')->with('final_price')->andReturn(850);
        $order->shouldReceive('getBasePrice')->andReturn(1000);
        $order->shouldReceive('save');

        $order->shouldReceive('setAttribute')->with('final_price', 850);

        $priceRuleRepository = Mockery::mock(EloquentPriceRuleRepository::class);
        $builtinPriceRuleRepository = Mockery::mock(EloquentBuiltinPriceRuleRepository::class);
        $pricingContext = Mockery::mock(PricingContext::class);

        $priceRuleRepository->shouldReceive('allActiveRules')->andReturn(new Collection());

        $builtinPriceRule = Mockery::mock(BuiltinPriceRule::class);

        $builtinPriceRuleRepository->shouldReceive('activeRule')
            ->withArgs(function ($strategy, $orderId, $discountValue) {
                return $strategy instanceof CategoryPricingDecorator
                    && $orderId == 1
                    && $discountValue == 10;
            })
            ->andReturn($builtinPriceRule);

        $builtinPriceRuleRepository->shouldReceive('activeRule')
            ->withArgs(function ($strategy, $orderId, $discountValue) {
                return $strategy instanceof LocationPricingDecorator
                    && $orderId == 1
                    && $discountValue == 5;
            })
            ->andReturn($builtinPriceRule);

        $builtinPriceRuleRepository->shouldReceive('activeRule')
            ->withArgs(function ($strategy, $orderId, $discountValue) {
                return $strategy instanceof SellerDiscountDecorator
                    && $orderId == 1
                    && $discountValue == 3;
            })
            ->andReturn($builtinPriceRule);

        $builtinPriceRuleRepository->shouldReceive('save')
            ->with(Mockery::type(BuiltinPriceRule::class))
            ->andReturn($builtinPriceRule);

        $pricingContext->shouldReceive('setStrategy')->andReturnNull();
        $pricingContext->shouldReceive('calculatePrice')->andReturn(850);

        $pricingStrategy = Mockery::mock(PricingStrategyInterface::class);
        $pricingContext->shouldReceive('getCurrentStrategy')->andReturn($pricingStrategy);

        $ruleEngine = new RuleEngine($priceRuleRepository, $builtinPriceRuleRepository, $pricingContext);

        $service = new PriceRuleService($ruleEngine);
        $result = $service->calculateFinalPrice($order);

        $this->assertEquals(850, $result);
    }


}
