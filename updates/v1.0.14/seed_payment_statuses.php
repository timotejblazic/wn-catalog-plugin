<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $statuses = [
            ['name' => 'Pending', 'code' => 'pending'],
            ['name' => 'Paid', 'code' => 'paid'],
            ['name' => 'Failed', 'code' => 'failed'],
        ];

        foreach ($statuses as $status) {
            DB::table('tb_catalog_payment_statuses')->insert($status);
        }
    }

    public function down()
    {
        DB::table('tb_catalog_payment_statuses')->whereIn('code', ['pending', 'paid', 'failed'])->delete();
    }
};
