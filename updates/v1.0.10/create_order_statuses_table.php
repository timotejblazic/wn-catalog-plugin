<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_catalog_order_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        $statuses = [
            ['name' => 'Pending',    'code' => 'pending'],
            ['name' => 'Processing', 'code' => 'processing'],
            ['name' => 'Completed',  'code' => 'completed'],
            ['name' => 'Cancelled',  'code' => 'cancelled'],
        ];

        foreach ($statuses as $status) {
            DB::table('tb_catalog_order_statuses')->insert($status);
        }
    }

    public function down()
    {
        Schema::dropIfExists('tb_catalog_order_statuses');
    }
};
