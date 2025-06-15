<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->nullable()->after('payment_status_id');
            $table->decimal('discount_amount',10,2)->default(0)->after('coupon_id');
            $table->foreign('coupon_id')
                ->references('id')->on('tb_catalog_coupons')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id','discount_amount']);
        });
    }
};
