public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('custom_product_id')->nullable()->after('product_id');
        $table->foreign('custom_product_id')->references('id')->on('custom_products')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['custom_product_id']);
        $table->dropColumn('custom_product_id');
    });
}