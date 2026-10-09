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
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->string('status')->default('pending')->index(); // pending, processing, shipped, delivered, cancelled
            $table->string('payment_status')->default('pending')->index(); // pending, paid, failed, refunded
            $table->string('payment_method')->default('card'); // card, cod, paypal, bank_transfer
            $table->string('payment_reference')->nullable();

            $table->string('shipping_method')->default('standard'); // standard, express, overnight
            $table->decimal('shipping_rate', 10, 2)->default(0.00);

            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);

            $table->json('shipping_address');
            $table->json('billing_address')->nullable();

            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->date('estimated_delivery')->nullable();

            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku');
            $table->string('product_image')->nullable();
            $table->json('variant_details')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('order_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status_title');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
        Schema::dropIfExists('order_trackings');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
