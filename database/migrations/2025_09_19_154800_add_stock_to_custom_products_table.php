<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockToCustomProductsTable extends Migration
{
    public function up()
    {
        Schema::table('custom_products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price');
        });
    }

    public function down()
    {
        Schema::table('custom_products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
}
