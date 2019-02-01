<?php

use Illuminate\Database\Migrations\Migration;

class CreateInternalMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('internal_messages', function($table)
        {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('conversation_id');
			$table->string('subject')->nullable();
            $table->text('content');
			$table->text('attachments');
            $table->timestamps();

            $table->index('sender_id');
            $table->index('conversation_id');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('internal_messages');
	}

}