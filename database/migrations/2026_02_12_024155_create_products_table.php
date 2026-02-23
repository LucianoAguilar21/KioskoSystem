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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('line_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->after('line_id')->constrained()->onDelete('set null');

            $table->index(['category_id', 'is_active']);
            $table->index('line_id');
            $table->index('brand_id');
            $table->index(['is_active', 'stock']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
