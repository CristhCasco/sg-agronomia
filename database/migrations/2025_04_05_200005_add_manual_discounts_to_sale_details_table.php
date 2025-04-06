<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualDiscountsToSaleDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->decimal('manual_price', 15, 2)->nullable()->after('sub_total');
            $table->decimal('manual_discount', 15, 2)->nullable()->after('manual_price');
            $table->decimal('manual_discount_percent', 15, 2)->nullable()->after('manual_discount');
        });
    }

    public function down()
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn(['manual_price', 'manual_discount', 'manual_discount_percent']);
        });
    }
}
