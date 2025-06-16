<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->unique();
            $table->string('name');
            $table->string('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_payment_methods');
    }
};
