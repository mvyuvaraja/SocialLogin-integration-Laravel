<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /*--------------------------
         * Table: users
         * -------------------------*/
        Schema::dropIfExists('users');
		Schema::create('users', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('email')->unique('email');
            $table->string('password')->index('password');
            $table->enum('registered_from', ['UI','Facebook', 'Google'])->default('UI')->index('registered_from');
            
            $table->timestamps();
            $table->rememberToken();
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
