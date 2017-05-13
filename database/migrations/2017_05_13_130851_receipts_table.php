<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('userID');
			$table->float('vatRate', 2, 2);
			$table->float('totalNet', 2, 2);
			$table->float('totalWVat', 2, 2);
			$table->integer('taxExempt')->default(0);
			$table->string('zki');
			$table->string('jir');
			$table->string('saleVenue');
			$table->string('saleOperator');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
			
			$table->foreign('userID')->references('id')->on('users');
		});
		
		Schema::create('invoiceItems', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('invoiceId');
			$table->string('description');
			$table->float('price', 2, 2);
			$table->string('currency');
			$table->integer('quantity');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
			
			$table->foreign('invoiceId')->references('id')->on('invoices');
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
