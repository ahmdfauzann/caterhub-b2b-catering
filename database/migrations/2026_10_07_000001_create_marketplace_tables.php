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
        // 1. Merchant Profiles Table
        Schema::create('merchant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->default('Jakarta');
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('cuisine_type')->nullable(); // e.g. "Indonesian, Western, Healthy"
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
            $table->timestamps();
        });

        // 2. Customer Profiles Table
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('pic_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('office_address')->nullable();
            $table->string('city')->default('Jakarta');
            $table->integer('employee_count')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Menus Table
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_profile_id')->constrained('merchant_profiles')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('photo')->nullable();
            $table->integer('min_portion')->default(10);
            $table->string('dietary_tags')->nullable(); // e.g., "Halal, Non-MSG, Low Calorie"
            $table->boolean('is_available')->default(true);
            $table->integer('sales_count')->default(0);
            $table->timestamps();
        });

        // 5. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('merchant_id')->constrained('merchant_profiles')->onDelete('cascade');
            $table->date('delivery_date');
            $table->string('delivery_time')->default('12:00 - Makan Siang');
            $table->text('delivery_address');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'delivering', 'delivered', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'verified'])->default('unpaid');
            $table->string('payment_method')->default('Corporate Billing');
            $table->string('payment_receipt')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('set null');
            $table->string('menu_name');
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Invoices Table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 8. Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('merchant_id')->constrained('merchant_profiles')->onDelete('cascade');
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customer_profiles');
        Schema::dropIfExists('merchant_profiles');
    }
};
