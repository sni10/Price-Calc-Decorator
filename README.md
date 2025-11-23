# Order dynamic price strategy decorator REST/API Backend (test)
[![pipeline status](https://gitlab.com/quotelike/backend-laravel/badges/main/pipeline.svg)](https://gitlab.com/quotelike/backend-laravel/-/commits/main)
[![coverage report](https://gitlab.com/quotelike/backend-laravel/badges/main/coverage.svg)](https://gitlab.com/quotelike/backend-laravel/-/commits/main)
[![Latest Release](https://gitlab.com/quotelike/backend-laravel/-/badges/release.svg)](https://gitlab.com/quotelike/backend-laravel/-/releases)

## Standards & Versions — MANDATORY

This project strictly follows the standards below. Contributions that do not meet these rules will not be accepted.

- Runtime/Framework:
  - PHP: 8.2.x (composer.json requires ^8.2)
  - Laravel: 11.x
  - PHPUnit: 11.x
- Coding Style: PSR-12 with Laravel preset (Laravel Pint)
  - Every PHP file MUST include: declare(strict_types=1);
  - Use modern PHP 8.2 syntax: constructor property promotion, union/nullable types, match, null coalescing, nullsafe, named arguments
  - DO NOT use legacy syntax: array() instead of [], ternary instead of null coalescing, sprintf where string interpolation is enough
- OOP/SOLID:
  - Single Responsibility, Open/Closed, Liskov, Interface Segregation, Dependency Inversion
  - Prefer composition over inheritance, validate configuration in constructors, avoid deep hierarchies
- Architectural patterns (STRICT):
  - Repository for data access (App\Contracts\*RepositoryInterface + App\Repositories\Eloquent*)
  - Strategy + Decorator for pricing (App\Services\PricingStrategy\...)
  - RuleEngine aggregates rule application

Automated style check/fix (inside php container):

```bash
composer run lint      # validate style with Pint (no changes)
composer run lint:fix  # auto-fix style
```

More details and DO/DON'T examples: .junie/guidelines.md (section “Обязательные стандарты и версии (MANDATORY)”).

## Disclaimer

Эта архитектура использует несколько ключевых паттернов проектирования, чтобы обеспечить модульность, гибкость и масштабируемость системы:

* Repository Pattern: Каждый репозиторий (например, ProductRepository, SellerRepository) отвечает за работу с конкретной сущностью (продукт, продавец, категория и т.д.) и реализует общий интерфейс RepositoryInterface. Это позволяет абстрагировать логику доступа к данным, изолируя бизнес-логику от деталей хранения. Благодаря этому можно легко менять источник данных (например, БД или API) без изменения бизнес-логики.
* Strategy Pattern: Паттерн стратегии реализован через интерфейс PricingStrategy, который описывает метод расчета цены. Конкретные стратегии, такие как BasePriceStrategy, декораторы для категорий, локаций и скидок, позволяют динамически подменять логику расчета цены. Это дает возможность адаптировать систему под разные сценарии, добавляя или меняя стратегию ценообразования без изменения основной логики заказа.
* Decorator Pattern: Декораторы (CategoryPricingDecorator, LocationPricingDecorator, VolumeDiscountDecorator, SellerDiscountDecorator) расширяют базовую логику расчета цены, добавляя дополнительные модификации (например, скидки или надбавки) поверх уже существующих стратегий. Это позволяет гибко комбинировать различные правила, не затрагивая базовый класс, и поддерживает принцип открытости/закрытости (OCP), когда новый функционал добавляется через композицию.
* Rule Engine: RuleEngine объединяет стратегии и декораторы для расчета окончательной цены заказа. Он отвечает за применение всех применимых правил и стратегий, что позволяет централизованно управлять процессом ценообразования. Это упрощает добавление новых правил или модификаций в будущем, не нарушая уже существующую логику.
Архитектура чётко разделяет ответственность: каждый репозиторий обособлен и отвечает только за свою сущность, стратегии и декораторы обеспечивают гибкость в расчетах, а правило "Один класс — одна ответственность" строго соблюдается. Все элементы связаны через интерфейсы и абстракции, что обеспечивает слабую связанность и высокую тестируемость системы.

### Architecture
![schema.png](schema.png)

### File structure diagram

<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```yaml
├── app/
│   ├── Contracts/
│   │   ├── BuiltinPriceRuleRepositoryInterface.php
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── CategoryServiceInterface.php
│   │   ├── LocationRepositoryInterface.php
│   │   ├── LocationServiceInterface.php
│   │   ├── OrderRepositoryInterface.php
│   │   ├── OrderServiceInterface.php
│   │   ├── PriceRuleRepositoryInterface.php
│   │   ├── PricingStrategyInterface.php
│   │   ├── ProductRepositoryInterface.php
│   │   ├── SellerRepositoryInterface.php
│   │   ├── SellerServiceInterface.php
│   │   ├── TokenRepositoryInterface.php
│   │   ├── TokenServiceInterface.php
│   │   ├── UserRepositoryInterface.php
│   │   └── UserServiceInterface.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── PricingController.php
│   │   │   └── Controller.php
│   ├── Models/
│   │   ├── BuiltinPriceRule.php
│   │   ├── Category.php
│   │   ├── Location.php
│   │   ├── Order.php
│   │   ├── PriceRule.php
│   │   ├── Product.php
│   │   ├── RefreshToken.php
│   │   ├── Seller.php
│   │   └── User.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Repositories/
│   │   ├── EloquentBuiltinPriceRuleRepository.php
│   │   ├── EloquentCategoryRepository.php
│   │   ├── EloquentLocationRepository.php
│   │   ├── EloquentOrderRepository.php
│   │   ├── EloquentPriceRuleRepository.php
│   │   ├── EloquentProductRepository.php
│   │   ├── EloquentSellerRepository.php
│   │   ├── EloquentTokenRepository.php
│   │   └── EloquentUserRepository.php
│   ├── Services/
│   │   ├── OrderService.php
│   │   ├── PriceRuleService.php
│   │   ├── PricingContext.php
│   │   ├── PricingStrategy/
│   │   │   ├── BasePriceStrategy.php
│   │   │   ├── CategoryPricingDecorator.php
│   │   │   ├── LocationPricingDecorator.php
│   │   │   ├── PriceDecorator.php
│   │   │   ├── SellerDiscountDecorator.php
│   │   │   └── VolumeDiscountDecorator.php
│   │   ├── RuleEngine.php
│   │   ├── SellerService.php
│   │   ├── TokenService.php
│   │   └── UserService.php
├── bootstrap/
│   ├── app.php
│   ├── cache/
│   │   ├── .gitignore
│   │   ├── config.php
│   │   ├── packages.php
│   │   └── services.php
│   └── providers.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 2024_08_30_121349_create_users_table.php
│   │   ├── 2024_08_30_121417_create_password_resets_table.php
│   │   ├── 2024_08_30_121449_create_personal_access_tokens_table.php
│   │   ├── 2024_09_04_215244_create_refresh_tokens_table.php
│   │   ├── 2024_10_11_110505_create_categories_table.php
│   │   ├── 2024_10_11_110511_create_locations_table.php
│   │   ├── 2024_10_11_110516_create_sellers_table.php
│   │   ├── 2024_10_11_110522_create_price_rules_table.php
│   │   ├── 2024_10_11_110528_create_products_table.php
│   │   ├── 2024_10_11_110533_create_orders_table.php
│   │   ├── 2024_10_11_154453_create_order_price_rules_table.php
│   │   ├── 2024_10_11_194129_create_built_in_price_rules_table.php
│   │   └── 2024_10_11_203054_create_order_builtin_price_rules_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php
├── nginx/
│   ├── default.conf
│   ├── php-fpm.conf
│   ├── php.ini
│   ├── snippets/
│   │   └── fastcgi-php.conf
├── public/
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── views/
│   │   └── welcome.blade.php
├── routes/
│   ├── api.php
│   └── console.php
├── storage/
│   ├── app/
│   │   ├── .gitignore
│   │   ├── public/
│   │   │   └── .gitignore
├── tests/
│   ├── Feature/
│   │   └── AuthTest.php
│   ├── Unit/
│   │   ├── Services/
│   │   │   └── PriceRuleServiceTest.php
│   │   └── UserTest.php
│   └── TestCase.php
├── .dockerignore
├── .editorconfig
├── .env
├── .env.example
├── .gitattributes
├── .gitignore
├── .gitlab-ci.yml
├── .phpunit.result.cache
├── Dockerfile
├── README.md
├── Schema.puml
├── artisan
├── composer.json
├── composer.lock
├── docker-compose.yml
├── package.json
├── phpunit.xml
├── rest-api.http
└── vite.config.js
```
</details>


### Dockerfile
[Dockerfile](Dockerfile)

<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    procps \
    net-tools \
    lsof \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    && docker-php-ext-install mbstring exif pcntl bcmath gd pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install redis && docker-php-ext-enable redis \
    && pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-install mbstring exif pcntl bcmath gd pdo pdo_mysql zip

#COPY ./nginx/php.ini /usr/local/etc/php/conf.d/custom-php.ini
#COPY ./nginx/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

COPY . /var/www

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

USER www-data

CMD ["php-fpm"]
```
</details>

Ready for build.

I've commented out the configurations for running the container locally.
```yaml
# COPY ./nginx/php.ini /usr/local/etc/php/conf.d/custom-php.ini
# COPY ./nginx/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
```

Uncomment them if you need to build an independent image
```yaml
COPY ./nginx/php.ini /usr/local/etc/php/conf.d/custom-php.ini
COPY ./nginx/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
```

### docker-compose.yml
[docker-compose.yml](docker-compose.yml)

Oriented towards local deployment, using volumes for that purpose.
```yaml
volumes:
    - .:/var/www # for the ability to locally plug-and-play modify project files for development
    - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    - ./nginx/snippets/fastcgi-php.conf:/etc/nginx/snippets/fastcgi-php.conf
```

All the necessary hosts for connecting to containers are specified in the environment files as well as in the Docker configurations. Everything is set to the default settings for simplicity.

## Run app localy
1. Clone the repository to your local project folder.
2. Check the volumes and config forwarding. Choose the one you prefer. For more information, see [Disclaimer](#disclaimer).
3. `docker-compose up --build -d` just use to run App

The application API should work at the address `http://localhost:8000`

`http:\\localhost:8000` By default it is set to the standard Laravel web stub

## Application composition (Services)
```                 q   
CONTAINER ID   IMAGE                    COMMAND                  CREATED      STATUS      PORTS                               NAMES
4691157ecc8b   quote-backend-php-test   "docker-php-entrypoi…"   4 days ago   Up 3 days   9000/tcp                            php-test
917c5a02080a   nginx:latest             "/docker-entrypoint.…"   4 days ago   Up 3 days   0.0.0.0:8000->80/tcp                nginx-test
c53a16c92c38   mysql:8.0.33             "docker-entrypoint.s…"   4 days ago   Up 3 days   0.0.0.0:3306->3306/tcp, 33060/tcp   mysql-test
11905f00a931   redis:7.0.7              "docker-entrypoint.s…"   4 days ago   Up 3 days   0.0.0.0:6379->6379/tcp              redis-test
```


## Routes of group `api/v1`
```php
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}', [OrderController::class, 'update']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

        Route::post('/calculate-price', [PricingController::class, 'calculatePrice']);
    });
});
```

## Authentication is based on the Sanctum library
In the future, it is expected that the logic of use will be expanded with the distribution of rights and
credentials for issued tokens

---

### Request example `http://localhost:8000/api/v1/register`
<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```php
POST http://localhost:8000/api/v1/register
Accept: application/json
Content-Type: application/json

{
  "name": "MYNAME",
  "email": "admin@admin.com",
  "password": "content123"
}

HTTP/1.1 201 Created
Server: nginx/1.27.1
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/8.2.23
Cache-Control: no-cache, private
Date: Tue, 03 Sep 2024 16:23:34 GMT
Access-Control-Allow-Origin: *


Response {
  "access_token": "2|4BrtGfUhacVxSSMFYKiaX6LMmUuRQu7pxrm8aUXY2ac15ad4",
  "token_type": "Bearer"
}
```
</details>

### Request example `http://localhost:8000/api/v1/login`
<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```php
POST http://localhost:8000/api/v1/login
Accept: application/json
Content-Type: application/json

{
    "email": "test@copy.com",
    "password": "password123"
}


Response {
  "access_token": "228|SEcZ4THB4BuakoQ8T4d8Ocn2xlCVc0tkYM4SJJK0bcf0e42b",
  "refresh_token": "180|abd1fcadb9120895c99de834129fef10e9c4c3538f265b95a8055f297f4e5871",
  "token_type": "Bearer"
}
```
</details>

### Request example `http://localhost:8000/api/v1/orders`

<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```php
POST http://localhost:8000/api/v1/orders
Accept: application/json
Content-Type: application/json
Authorization: Bearer 229|hpGEnv0QcB95HQBejICxNsXo4n6Z4lxgHEsk0mId3a0496f8
content-length: 0

Response {
  "status": "success",
  "data": {
    "order_id": 7
  }
}

```
</details>

### Request example `http://localhost:8000/api/v1/calculate-price`

<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```php
POST http://localhost:8000/api/v1/calculate-price
Accept: application/json
Content-Type: application/json
Authorization: Bearer 229|hpGEnv0QcB95HQBejICxNsXo4n6Z4lxgHEsk0mId3a0496f8
Content-Length: 131
{
  "order_id": 7,
  "category_id": 1,
  "location_id": 1,
  "quantity": 30,
  "base_price": 1000.0,
  "apply_seller_discount": 1
}

Response {
  "status": "success",
  "data": {
    "final_price": 787.3296,
    "order_id": 7,
    "order": {
      "id": 7,
      "seller_id": 7,
      "location_id": 1,
      "category_id": 1,
      "quantity": 30,
      "base_price": 1000,
      "final_price": 787.3296,
      "apply_seller_discount": 1,
      "created_at": "2024-10-15T12:48:34.000000Z",
      "updated_at": "2024-10-15T12:48:44.000000Z",
      "category": {
        "id": 1,
        "name": "Motors",
        "discount": "5.00",
        "created_at": "2024-10-11T12:58:07.000000Z",
        "updated_at": "2024-10-11T12:58:07.000000Z"
      },
      "location": {
        "id": 1,
        "name": "Calabria",
        "discount": "4.00",
        "created_at": "2024-10-11T12:58:07.000000Z",
        "updated_at": "2024-10-11T12:58:07.000000Z"
      },
      "seller": {
        "id": 7,
        "name": "Test User copy",
        "personal_discount": "11.00",
        "user_id": 2,
        "created_at": "2024-10-15T12:48:34.000000Z",
        "updated_at": "2024-10-15T12:48:34.000000Z"
      }
    },
    "applied_rules": [
      {
        "rule_name": "Order quantity count up to 10 pieces",
        "discount_type": "percent",
        "discount_value": "3.00",
        "condition_type": ">",
        "condition_value": "10",
        "description": "Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied + Seller Discount Rule Applied + Volume discount for orders over 10",
        "pivot": {
          "order_id": 8,
          "price_rule_id": 1,
          "adjustment_amount": "-24.35040000",
          "created_at": "2024-10-15T12:48:44.000000Z",
          "updated_at": "2024-10-15T12:48:44.000000Z"
        }
      }
    ],
    "bapplied_rules": [
      {
        "rule_name": "CategoryPricingDecorator",
        "discount_type": "percent",
        "discount_value": "5.00",
        "description": "Base price calculation + Category Pricing Rule Applied",
        "pivot": {
          "order_id": 8,
          "builtin_price_rule_id": 1,
          "discount_amount": "-50.00000000",
          "created_at": "2024-10-15T12:48:44.000000Z",
          "updated_at": "2024-10-15T12:48:44.000000Z"
        }
      },
      {
        "rule_name": "LocationPricingDecorator",
        "discount_type": "percent",
        "discount_value": "4.00",
        "description": "Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied",
        "pivot": {
          "order_id": 8,
          "builtin_price_rule_id": 2,
          "discount_amount": "-38.00000000",
          "created_at": "2024-10-15T12:48:44.000000Z",
          "updated_at": "2024-10-15T12:48:44.000000Z"
        }
      },
      {
        "rule_name": "SellerDiscountDecorator",
        "discount_type": "percent",
        "discount_value": "11.00",
        "description": "Base price calculation + Category Pricing Rule Applied + Location Pricing Rule Applied + Seller Discount Rule Applied",
        "pivot": {
          "order_id": 8,
          "builtin_price_rule_id": 3,
          "discount_amount": "-100.32000000",
          "created_at": "2024-10-15T12:48:44.000000Z",
          "updated_at": "2024-10-15T12:48:44.000000Z"
        }
      }
    ]
  }
}

```
</details>

## Another Examples of requests to a group `api/v1`

```
http://localhost:8000/api/v1/orders/15

```

---
## Tests
- Tests must be run inside the php container
- Login to php container `docker exec -it php /bin/bash`

### UnitTest
- For run directory `./storage/coverage-report` must be writeable on you local host machine
- `vendor/bin/phpunit --coverage-text --colors=always --testdox` run tests
- Open `storage/coverage-report/index.html` for detail analyze reports
---
#### Output example UnitTest

<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```shell
www-data@4691157ecc8b:~$ vendor/bin/phpunit --coverage-text --colors=always --testdox
PHPUnit 11.4.1 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.24 with Xdebug 3.3.2
Configuration: /var/www/phpunit.xml

.....                                                               5 / 5 (100%)

Time: 00:14.072, Memory: 40.00 MB

OK (5 tests, 19 assertions)

Generating code coverage report in Clover XML format ... done [00:00.451]

Generating code coverage report in HTML format ... done [00:10.098]


Code Coverage Report:      
  2024-10-15 09:34:36      
                           
 Summary:                  
  Classes:  8.57% (3/35)   
  Methods: 19.87% (31/156) 
  Lines:   26.50% (150/566)

App\Http\Controllers\Api\AuthController
  Methods:  20.00% ( 1/ 5)   Lines:  56.36% ( 31/ 55)
App\Models\User
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  4/  4)
App\Providers\AppServiceProvider
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% ( 40/ 40)
App\Repositories\EloquentTokenRepository
  Methods:  45.45% ( 5/11)   Lines:  41.67% ( 20/ 48)
App\Repositories\EloquentUserRepository
  Methods:  42.86% ( 3/ 7)   Lines:  35.00% (  7/ 20)
App\Services\PriceRuleService
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  2/  2)
App\Services\PricingStrategy\CategoryPricingDecorator
  Methods:  33.33% ( 1/ 3)   Lines:   8.33% (  1/ 12)
App\Services\PricingStrategy\LocationPricingDecorator
  Methods:  33.33% ( 1/ 3)   Lines:   8.33% (  1/ 12)
App\Services\PricingStrategy\PriceDecorator
  Methods:  50.00% ( 1/ 2)   Lines:  25.00% (  1/  4)
App\Services\PricingStrategy\SellerDiscountDecorator
  Methods:  33.33% ( 1/ 3)   Lines:   8.33% (  1/ 12)
App\Services\RuleEngine
  Methods:  50.00% ( 3/ 6)   Lines:  71.74% ( 33/ 46)
App\Services\TokenService
  Methods:  55.56% ( 5/ 9)   Lines:  55.56% (  5/  9)
App\Services\UserService
  Methods:  57.14% ( 4/ 7)   Lines:  50.00% (  4/  8)


```
</details>

---

### FeatureTest (E2E)
- Use `php artisan test` or for run default laravel test handler
- OR `php artisan test --filter=AuthTest` For run tests partly and pointary

#### Output example FeatureTest
<details>
  <summary>---------------------------------------------- - EXPAND CODE BLOCK - ----------------------------------------------</summary>

```shell
www-data@4691157ecc8b:~$ php artisan test

   PASS  Tests\Unit\Services\PriceRuleServiceTest
  ✓ calculate final price with location and category                                                                                                                                                                                                 4.38s  

   PASS  Tests\Unit\UserTest
  ✓ user creation                                                                                                                                                                                                                                    0.84s  

   PASS  Tests\Feature\AuthTest
  ✓ registration                                                                                                                                                                                                                                     1.69s  
  ✓ login                                                                                                                                                                                                                                            0.98s  
  ✓ logout                                                                                                                                                                                                                                           1.26s  

  Tests:    5 passed (19 assertions)
  Duration: 10.72s
```
</details>

## DevOps
### CI/CD
>[.gitlab-ci.yml](.gitlab-ci.yml) 
> 
> Automated content delivery configuration file for Gitlab

### Pipelines with Autotests
> https://gitlab.com/quotelike/backend-laravel/-/pipelines
> 
> Free access to list tasks, because is it opensource project
