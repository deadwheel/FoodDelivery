<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DriverChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		//Schema::table('orders', function (Blueprint $table) {
          //  $table->dropColumn('driver_loc');
			//$table->dropColumn('deliverer_id');
        //});
		
		
		Schema::create('order_driver', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('deliverer_id');
			$table->integer('order_id');
			$table->string('driver_loc');
            $table->foreign('deliverer_id')->references('id')->on('users')->onDelete('NO ACTION');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_driver');
    }
}
