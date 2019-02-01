<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactRequestsTable extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
	  Schema::create('contact_request_categories', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('created_by')->unsigned()->unique()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
         });
         Schema::create('contact_request_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_request_category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(array('contact_request_category_id','locale'),'unique_trans');
            $table->foreign('contact_request_category_id','foreign_trans')->references('id')->on('contact_request_categories')->onDelete('cascade');
            $table->timestamps();
         });
		
         Schema::create('contact_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_subject');
            $table->text('contact_message');
            $table->text('contact_attachment')->nullable();
            $table->integer('contact_request_category')->unsigned()->index()->nullable();
            $table->integer('contacted_by')->unsigned()->index()->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('reference_no')->unique();
            $table->timestamps();
            $table->foreign('contacted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->foreign('contact_request_category')->references('id')->on('contact_request_categories')->onDelete('set null')->onUpdate('set null');
        });
        
        Schema::create('contact_request_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reply_subject');
            $table->text('reply_message');
            $table->text('reply_attachment')->nullable();
            $table->integer('contact_request_id')->unsigned()->index()->nullable();
            $table->integer('from_user_id')->unsigned()->index()->nullable();
            $table->string('reply_email');
            $table->timestamps();
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->foreign('contact_request_id')->references('id')->on('contact_requests')->onDelete('cascade')->onUpdate('cascade');
        });
      }
	
  public function down()
    {
            Schema::drop('contact_request_categories');
            Schema::drop('contact_requests');
            Schema::drop('contact_requests_replies');
            Schema::drop('contact_request_category_translations');
    }
}