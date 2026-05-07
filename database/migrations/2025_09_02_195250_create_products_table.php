<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('category')->nullable();
        $table->text('description')->nullable();
        $table->decimal('price', 15, 2);
        $table->integer('stock')->default(0);
        $table->string('image')->nullable();
        // Langsung masukkan biaya desain di sini
        $table->decimal('small_design_cost', 10, 2)->default(0);  // A5
        $table->decimal('medium_design_cost', 10, 2)->default(0); // A4
        $table->decimal('large_design_cost', 10, 2)->default(0);  // A3
        $table->timestamps();
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
