<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
	 /**
     * Run the migrations.
     * This file create emplail template table
     * @return void
     */
    public function up()
    {
		
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->string('locale');
            $table->text('html_content');
            $table->string('template_key')->unique();
            $table->text('template_keywords');
            $table->timestamps();
        });
    }
	
  public function down()
    {
        Schema::drop('email_templates');
    }
}