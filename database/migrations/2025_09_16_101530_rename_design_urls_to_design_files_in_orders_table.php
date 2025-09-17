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
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('design_url_depan', 'design_file_depan');
            $table->renameColumn('design_url_belakang', 'design_file_belakang');
            $table->renameColumn('design_url_samping', 'design_file_samping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('design_file_depan', 'design_url_depan');
            $table->renameColumn('design_file_belakang', 'design_url_belakang');
            $table->renameColumn('design_file_samping', 'design_url_samping');
        });
    }
};