<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('design_url_depan')->nullable()->after('total_price');
            $table->text('design_url_belakang')->nullable()->after('design_url_depan');
            $table->text('design_url_samping')->nullable()->after('design_url_belakang');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['design_url_depan', 'design_url_belakang', 'design_url_samping']);
        });
    }
};