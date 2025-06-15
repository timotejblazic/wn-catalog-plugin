<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->integer('delivery_method_id')->unsigned()->nullable()->after('billing_address');
            $table->decimal('shipping_cost', 10, 2)->default(0);

            $table->foreign('delivery_method_id')
                ->references('id')->on('tb_catalog_delivery_methods')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_method_id']);
            $table->dropColumn(['delivery_method_id','shipping_cost']);
        });
    }
};
