<?php

use Illuminate\Database\Migrations\Migration;

class CreateInternalMessageStatuses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('internal_message_statuses', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('internal_message_id');
            $table->boolean('self');
            $table->integer('status');

            $table->index('internal_message_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('internal_message_statuses');
	}

}