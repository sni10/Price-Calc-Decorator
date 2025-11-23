<?php

namespace App\Providers;

use App\Contracts\BuiltinPriceRuleRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\LocationRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\PriceRuleRepositoryInterface;
use App\Contracts\PriceRuleServiceInterface;
use App\Contracts\PricingStrategyInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\SellerRepositoryInterface;
use App\Contracts\SellerServiceInterface;
use App\Contracts\TokenRepositoryInterface;
use App\Contracts\TokenServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\UserServiceInterface;
use App\Repositories\EloquentBuiltinPriceRuleRepository;
use App\Repositories\EloquentCategoryRepository;
use App\Repositories\EloquentLocationRepository;
use App\Repositories\EloquentOrderRepository;
use App\Repositories\EloquentPriceRuleRepository;
use App\Repositories\EloquentProductRepository;
use App\Repositories\EloquentSellerRepository;
use App\Repositories\EloquentTokenRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\OrderService;
use App\Services\PriceRuleService;
use App\Services\PricingStrategy\BasePriceStrategy;
use App\Services\SellerService;
use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );
        $this->app->bind(
            LocationRepositoryInterface::class,
            EloquentLocationRepository::class
        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            EloquentOrderRepository::class
        );
        $this->app->bind(
            PriceRuleRepositoryInterface::class,
            EloquentPriceRuleRepository::class
        );
        $this->app->bind(
            BuiltinPriceRuleRepositoryInterface::class,
            EloquentBuiltinPriceRuleRepository::class
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
        $this->app->bind(
            SellerRepositoryInterface::class,
            EloquentSellerRepository::class
        );
        $this->app->bind(
            TokenRepositoryInterface::class,
            EloquentTokenRepository::class
        );

        $this->app->bind(SellerServiceInterface::class, SellerService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(TokenServiceInterface::class, TokenService::class);

        $this->app->bind(PriceRuleServiceInterface::class, PriceRuleService::class);

        $this->app->bind(PricingStrategyInterface::class, BasePriceStrategy::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
