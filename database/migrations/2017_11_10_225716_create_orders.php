<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::disableForeignKeyConstraints();
		
        Schema::create('orders', function (Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('deliverer_id')->nullable();
            $table->boolean('state')->nullable();
            $table->string('location');
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
