<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->double('price', 6, 2);
			$table->integer('tags_limit');
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
		
		Schema::table('users', function (Blueprint $table) {
            $table->integer('package_id')->unsigned()->nullable()->after('remember_token');
			$table->index('package_id');
			
			$table->foreign('package_id')
					->references('id')->on('packages')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('package_id');
        });
		
		Schema::dropIfExists('packages');
    }
}
