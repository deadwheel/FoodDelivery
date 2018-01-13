<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTimeStampsOrderOffer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('orrderoffer', function (Blueprint $table) {
            $table->dropTimestamps();
        });
		
		Schema::table('offers', function (Blueprint $table) {
            $table->dropTimestamps();
        });
		
		
		Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
		
		
		Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('update_time');
        });
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
