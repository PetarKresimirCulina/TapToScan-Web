<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_orders', function (Blueprint $table) {
			$table->unsignedInteger('invoice_id')->nullable();
			
			$table->foreign('invoice_id')
				  ->references('id')->on('invoices')
				  ->onDelete('cascade')
				  ->onUpdate('cascade');
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
