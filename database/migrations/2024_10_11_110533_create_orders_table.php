<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->decimal('base_price', 20, 8)->nullable();
            $table->decimal('final_price', 20, 8)->nullable();
            $table->boolean('apply_seller_discount')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
