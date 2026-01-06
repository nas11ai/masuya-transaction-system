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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment'])->comment('in: purchase/receipt, out: sales, adjustment: stock correction');
            $table->integer('qty')->comment('Positive for in/adjustment up, negative for out/adjustment down');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reference_no', 100)->nullable()->comment('PO number, invoice number, or adjustment reference');
            $table->text('notes')->nullable();
            $table->timestamp('created_at');

            $table->index('product_id');
            $table->index('transaction_id');
            $table->index('user_id');
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
