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
        Schema::create('price_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('restrict');
    $table->decimal('old_cost_price', 10, 2);
    $table->decimal('new_cost_price', 10, 2);
    $table->decimal('old_sale_price', 10, 2);
    $table->decimal('new_sale_price', 10, 2);
    $table->timestamps();
    
    $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_histories');
    }
};
