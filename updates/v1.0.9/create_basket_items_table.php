<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_basket_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('basket_id')->unsigned();
            $table->integer('product_variant_id')->unsigned();
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();

            $table->foreign('basket_id')
                ->references('id')->on('tb_catalog_baskets')
                ->onDelete('cascade');
            $table->foreign('product_variant_id')
                ->references('id')->on('tb_catalog_product_variants')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_basket_items');
    }
};
