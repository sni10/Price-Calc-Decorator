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
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_type');
            $table->string('rule_name');
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('entity_id');
            $table->string('discount_type');
            $table->decimal('discount_value', 5, 2);
            $table->string('condition_type')->nullable();
            $table->text('condition_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
