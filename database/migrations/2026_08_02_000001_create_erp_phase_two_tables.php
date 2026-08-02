<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('erp_companies', function (Blueprint $table) {
            $table->id();
            $table->string('trade_name');
            $table->string('legal_name');
            $table->string('cnpj', 18)->unique();
            $table->string('state_registration')->nullable();
            $table->string('municipal_registration')->nullable();
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email');
            $table->string('zip_code', 9);
            $table->string('address');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('city');
            $table->string('state', 2);
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 7)->default('#0d6efd');
            $table->string('secondary_color', 7)->default('#6c757d');
            $table->string('business_hours');
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('daily_goal', 12, 2)->default(0);
            $table->decimal('monthly_goal', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('erp_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('erp_companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('cnpj', 18);
            $table->string('contact');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('category');
            $table->text('observations')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'cnpj']);
        });

        Schema::create('erp_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('erp_companies')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('erp_suppliers')->nullOnDelete();
            $table->string('name');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('internal_code');
            $table->string('barcode')->nullable();
            $table->enum('unit', ['g', 'kg', 'ml', 'l', 'unit']);
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('purchased_quantity', 14, 3);
            $table->decimal('current_quantity', 14, 3)->default(0);
            $table->decimal('minimum_quantity', 14, 3)->default(0);
            $table->decimal('loss_percentage', 5, 2)->default(0);
            $table->decimal('yield_percentage', 5, 2)->default(100);
            $table->decimal('unit_cost', 14, 6);
            $table->decimal('average_cost', 14, 6)->default(0);
            $table->date('expiration_date')->nullable();
            $table->string('batch')->nullable();
            $table->string('stock_location')->nullable();
            $table->text('observations')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'internal_code']);
        });

        Schema::create('erp_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('erp_companies')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('erp_ingredients')->cascadeOnDelete();
            $table->enum('type', ['entry', 'exit', 'loss', 'adjustment', 'transfer']);
            $table->decimal('quantity', 14, 3);
            $table->decimal('unit_cost', 14, 6)->default(0);
            $table->decimal('previous_quantity', 14, 3);
            $table->decimal('new_quantity', 14, 3);
            $table->string('origin_location')->nullable();
            $table->string('destination_location')->nullable();
            $table->text('notes')->nullable();
            $table->morphs('reference');
            $table->timestamps();
        });

        Schema::create('erp_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('erp_companies')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('erp_suppliers')->restrictOnDelete();
            $table->date('purchase_date');
            $table->string('invoice_number')->nullable();
            $table->decimal('freight', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('taxes', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->timestamps();
        });

        Schema::create('erp_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('erp_purchases')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('erp_ingredients')->restrictOnDelete();
            $table->decimal('quantity', 14, 3);
            $table->decimal('value', 12, 2);
            $table->decimal('unit_cost', 14, 6);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_purchase_items');
        Schema::dropIfExists('erp_purchases');
        Schema::dropIfExists('erp_stock_movements');
        Schema::dropIfExists('erp_ingredients');
        Schema::dropIfExists('erp_suppliers');
        Schema::dropIfExists('erp_companies');
    }
};
