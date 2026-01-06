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
        Schema::create('transaction_detail_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_detail_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_type_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('sequence')->comment('Order of discount application (1, 2, 3, ...)');
            $table->string('discount_name', 100);
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 15, 2)->comment('Percentage (e.g., 10 for 10%) or fixed amount');
            $table->decimal('discount_amount', 15, 2)->comment('Calculated discount amount');
            $table->timestamps();

            $table->index('transaction_detail_id');
            $table->index(
                ['transaction_detail_id', 'sequence'],
                'td_discounts_trx_detail_seq_idx'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_detail_discounts');
    }
};
