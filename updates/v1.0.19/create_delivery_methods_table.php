<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_delivery_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('cost', 10, 2);
            $table->decimal('min_weight', 8, 2)->nullable();
            $table->decimal('free_over_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_delivery_methods');
    }
};
