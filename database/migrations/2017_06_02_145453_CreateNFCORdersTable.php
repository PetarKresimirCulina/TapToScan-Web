<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNFCORdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_orders', function ($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('shipping_address');
			$table->string('shipping_zip');
			$table->string('shipping_city');
			$table->string('shipping_country');
			$table->integer('quantity');
			$table->integer('paid')->default(0);
			$table->integer('shipped')->default(0);
			$table->timestamps();
			
			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
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
