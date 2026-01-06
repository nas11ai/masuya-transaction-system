<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();

            $table->string('product_code', 50);
            $table->string('product_name');

            $table->integer('qty');
            $table->decimal('price', 15, 2)->comment('Can be edited, different from master product price');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('Total discount amount (sum of all cascading discounts)');
            $table->decimal('net_price', 15, 2)->comment('Price after all discounts');
            $table->decimal('subtotal', 15, 2)->comment('net_price * qty');
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
