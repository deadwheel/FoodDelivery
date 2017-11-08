<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         
		 Schema::create('offers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description');
			$table->binary('image')->nullable();
			$table->double('price',15,2);
			$table->timestamps();
		
        });
				
		Schema::create('orders', function (Blueprint $table) {
	
            $table->increments('id')->unsigned();
			$table->integer('user_id')->nullable();
		    $table->integer('deliverer_id')->nullable();
			$table->boolean('state')->nullable();
			$table->string('location');
			$table->softDeletes()->nullable();
            $table->timestamps();
        });
		
		 Schema::create('orderoffer', function (Blueprint $table) {
			
            $table->increments('id')->unsigned();
			$table->integer('offer_id')->unsigned()->nullable();
			$table->integer('order_id')->unsigned()->nullable();
			$table->integer('quantity')->nullable();
			$table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('orderoffer');
    }
}
