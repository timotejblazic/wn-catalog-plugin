<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tb_catalog_products', function(Blueprint $table) {
            $table->string('discount_type')->nullable()->after('base_price');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
        });
    }

    public function down()
    {
        Schema::table('tb_catalog_products', function(Blueprint $table) {
            $table->dropColumn(['discount_type','discount_value']);
        });
    }
};
