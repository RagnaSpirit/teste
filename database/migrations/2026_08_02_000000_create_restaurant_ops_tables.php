<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('restaurant_tables', fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->string('status')->default('free'),$t->unsignedInteger('capacity')->default(2),$t->json('position')->nullable(),$t->timestamps()]);
  Schema::create('restaurant_tabs', fn(Blueprint $t)=>[$t->id(),$t->string('code')->unique(),$t->string('status')->default('open'),$t->foreignId('restaurant_table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete(),$t->decimal('total_amount',12,3)->default(0),$t->timestamp('closed_at')->nullable(),$t->timestamps()]);
  Schema::create('restaurant_orders', fn(Blueprint $t)=>[$t->id(),$t->string('type'),$t->string('status')->index(),$t->string('customer_name')->nullable(),$t->foreignId('table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete(),$t->foreignId('tab_id')->nullable()->constrained('restaurant_tabs')->nullOnDelete(),$t->json('items'),$t->json('payments')->nullable(),$t->string('coupon_code')->nullable(),$t->decimal('subtotal_amount',12,3)->default(0),$t->decimal('discount_amount',12,3)->default(0),$t->decimal('total_amount',12,3)->default(0),$t->decimal('change_amount',12,3)->default(0),$t->text('notes')->nullable(),$t->unsignedTinyInteger('priority')->default(0),$t->timestamp('closed_at')->nullable(),$t->timestamps()]);
  Schema::create('restaurant_order_status_histories', fn(Blueprint $t)=>[$t->id(),$t->foreignId('restaurant_order_id')->constrained('restaurant_orders')->cascadeOnDelete(),$t->string('from_status')->nullable(),$t->string('to_status'),$t->string('actor_type')->default('system'),$t->unsignedBigInteger('actor_id')->nullable(),$t->text('notes')->nullable(),$t->timestamps()]);
  Schema::create('restaurant_ingredients', fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->string('unit'),$t->decimal('stock_quantity',12,3)->default(0),$t->timestamps()]);
  Schema::create('restaurant_recipe_ingredients', fn(Blueprint $t)=>[$t->id(),$t->unsignedBigInteger('item_id')->index(),$t->foreignId('restaurant_ingredient_id')->constrained('restaurant_ingredients')->cascadeOnDelete(),$t->decimal('quantity',12,3),$t->timestamps()]);
  Schema::create('restaurant_print_jobs', fn(Blueprint $t)=>[$t->id(),$t->foreignId('restaurant_order_id')->constrained('restaurant_orders')->cascadeOnDelete(),$t->string('sector'),$t->string('paper_width'),$t->json('payload'),$t->string('status')->default('queued'),$t->timestamp('printed_at')->nullable(),$t->timestamps()]);
  Schema::create('restaurant_permission_profiles', fn(Blueprint $t)=>[$t->id(),$t->string('role')->unique(),$t->json('permissions'),$t->timestamps()]);
 }
 public function down(): void { foreach(['restaurant_permission_profiles','restaurant_print_jobs','restaurant_recipe_ingredients','restaurant_ingredients','restaurant_order_status_histories','restaurant_orders','restaurant_tabs','restaurant_tables'] as $table) Schema::dropIfExists($table); }
};
