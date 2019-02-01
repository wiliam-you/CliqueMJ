<?php

use Illuminate\Database\Migrations\Migration;

class CreateConversationUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_users', function($table)
        {
            $table->integer('conversation_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->primary(array('conversation_id', 'user_id'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conversation_users');
    }

}