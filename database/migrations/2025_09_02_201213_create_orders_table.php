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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity');
        $table->string('size');
        $table->string('color');
        $table->decimal('total_price', 15, 2);
        $table->enum('status', ['pending', 'proses', 'selesai', 'ditolak'])->default('pending');
        $table->text('notes')->nullable(); // Untuk link bahan HD & deskripsi
        $table->string('design_file_depan')->nullable();
        $table->string('design_file_belakang')->nullable();
        $table->string('design_file_samping')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
