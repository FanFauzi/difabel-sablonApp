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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('small_design_cost')->default(0)->after('stock');
            $table->unsignedInteger('medium_design_cost')->default(0)->after('small_design_cost');
            $table->unsignedInteger('large_design_cost')->default(0)->after('medium_design_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('small_design_cost');
            $table->dropColumn('medium_design_cost');
            $table->dropColumn('large_design_cost');
        });
    }
};