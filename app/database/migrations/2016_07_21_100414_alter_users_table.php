<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration {

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
        
		Schema::table('users', function($table)
        {
            $table->dropColumn(['email', 'password', 'registered_from']);
        });
        
        /*--------------------------
         * Table: user_credentials
         * -------------------------*/
		Schema::create('user_credentials', function($table)
        {
            $table->engine = 'InnoDB';
            
            $table->integer('user_id')->unsigned('user_id');
            $table->string('email')->unique('email');
            $table->string('password')->index('password');
            $table->enum('registered_from', ['UI','Facebook', 'Google', 'Twitter'])->default('UI')->index('registered_from');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
