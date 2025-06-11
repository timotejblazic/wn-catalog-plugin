<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('basket_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned();
            $table->decimal('total_amount', 10, 2);
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->timestamps();

            $table->foreign('basket_id')
                ->references('id')->on('tb_catalog_baskets')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');
            $table->foreign('status_id')
                ->references('id')->on('tb_catalog_order_statuses')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_orders');
    }
};
