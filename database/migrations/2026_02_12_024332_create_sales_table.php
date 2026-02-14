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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('cash_register_session_id')->constrained()->onDelete('restrict');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'mixed']);
            $table->decimal('cash_amount', 10, 2)->default(0);
            $table->decimal('card_amount', 10, 2)->default(0);
            $table->decimal('transfer_amount', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['cash_register_session_id', 'created_at']);
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
