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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_store_id')->nullable()
                ->constrained('stores')
                ->nullOnDelete();
            $table->string('name');
            $table->enum('level', ['pusat', 'cabang', 'retail']);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained();
            $table->foreignId('role_id')->constrained();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('cashier_id')->constrained('users');
            $table->decimal('total_price', 12, 2);
            $table->decimal('payment', 12, 2);
            $table->decimal('change', 12, 2);
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->decimal('price', 12, 2);
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('users');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sale_items');
        // Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
