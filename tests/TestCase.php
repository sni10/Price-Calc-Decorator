<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('orders')->truncate();
        DB::table('order_price_rules')->truncate();
        DB::table('order_builtin_price_rules')->truncate();
        DB::table('builtin_price_rules')->truncate();
        DB::table('price_rules')->truncate();
        DB::table('sellers')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->user = User::firstOrCreate([
            'email' => 'test@test.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password123')
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;

    }

    protected function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
        Mockery::close();
        parent::tearDown();
    }

}
