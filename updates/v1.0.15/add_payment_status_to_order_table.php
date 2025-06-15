<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->integer('payment_status_id')->unsigned()->nullable()->after('status_id');
            $table->foreign('payment_status_id')
                ->references('id')->on('tb_catalog_payment_statuses')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('tb_catalog_orders', function (Blueprint $table) {
            $table->dropForeign(['payment_status_id']);
            $table->dropColumn('payment_status_id');
        });
    }
};
