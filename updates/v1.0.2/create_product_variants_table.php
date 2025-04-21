<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_product_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_type')->nullable();
            $table->integer('product_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_product_variants');
    }
};
