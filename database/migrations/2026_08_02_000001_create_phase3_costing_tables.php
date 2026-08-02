<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('unit', 20);
            $table->decimal('current_price', 12, 4)->default(0);
            $table->decimal('average_price', 12, 4)->default(0);
            $table->decimal('package_quantity', 12, 4)->default(1);
            $table->decimal('yield_percent', 8, 4)->default(100);
            $table->decimal('loss_percent', 8, 4)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_configurations', function (Blueprint $table) {
            $table->id();
            $table->decimal('desired_profit_percent', 8, 4)->default(30);
            $table->decimal('tax_percent', 8, 4)->default(0);
            $table->decimal('card_fee_percent', 8, 4)->default(0);
            $table->decimal('pix_fee_percent', 8, 4)->default(0);
            $table->decimal('ifood_fee_percent', 8, 4)->default(0);
            $table->decimal('fox_go_fee_percent', 8, 4)->default(0);
            $table->decimal('delivery_fee_percent', 8, 4)->default(0);
            $table->decimal('marketplace_commission_percent', 8, 4)->default(0);
            $table->decimal('fixed_cost_percent', 8, 4)->default(0);
            $table->decimal('safety_margin_percent', 8, 4)->default(0);
            $table->decimal('packaging_cost', 12, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('product_cost_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->unique()->constrained('items')->cascadeOnDelete();
            $table->boolean('manual_price_enabled')->default(false);
            $table->decimal('manual_price', 12, 4)->nullable();
            $table->boolean('delivery_product')->default(true);
            $table->boolean('counter_product')->default(true);
            $table->boolean('promotion_product')->default(false);
            $table->unsignedInteger('preparation_time')->default(0);
            $table->decimal('final_weight', 12, 4)->default(0);
            $table->text('observations')->nullable();
            $table->decimal('packaging_cost', 12, 4)->default(0);
            $table->decimal('gas_cost', 12, 4)->default(0);
            $table->decimal('energy_cost', 12, 4)->default(0);
            $table->decimal('labor_cost', 12, 4)->default(0);
            $table->json('calculated_prices')->nullable();
            $table->timestamps();
        });

        Schema::create('technical_sheet_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->restrictOnDelete();
            $table->decimal('quantity', 12, 4);
            $table->string('unit', 20);
            $table->decimal('loss_percent', 8, 4)->default(0);
            $table->decimal('yield_percent', 8, 4)->default(100);
            $table->decimal('unit_cost_snapshot', 12, 6)->default(0);
            $table->decimal('total_cost', 12, 4)->default(0);
            $table->timestamps();
            $table->unique(['item_id', 'ingredient_id']);
        });

        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->json('calculated_prices')->nullable();
            $table->timestamps();
        });

        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained('combos')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->decimal('quantity', 12, 4)->default(1);
            $table->timestamps();
            $table->unique(['combo_id', 'item_id']);
        });

        Schema::create('additives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->restrictOnDelete();
            $table->decimal('quantity', 12, 4)->default(0);
            $table->string('unit', 20)->default('g');
            $table->decimal('cmv', 12, 4)->default(0);
            $table->decimal('profit', 12, 4)->default(0);
            $table->decimal('automatic_price', 12, 4)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additives');
        Schema::dropIfExists('combo_items');
        Schema::dropIfExists('combos');
        Schema::dropIfExists('technical_sheet_ingredients');
        Schema::dropIfExists('product_cost_profiles');
        Schema::dropIfExists('pricing_configurations');
        Schema::dropIfExists('ingredients');
    }
};