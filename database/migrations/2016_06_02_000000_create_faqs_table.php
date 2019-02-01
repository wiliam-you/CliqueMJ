<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateFaqsTable extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
            Schema::create('faq_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->string('cat_url',250);
            $table->integer('level');
            NestedSet::columns($table);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
			
        });
        
        Schema::create('faq_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('faq_category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(array('faq_category_id','locale'));
            $table->foreign('faq_category_id')->references('id')->on('faq_categories')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('faq_category_id')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
			
        });
		
	Schema::create('faq_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->text('answer');
            $table->string('locale')->index();
            $table->integer('faq_id')->unsigned()->index();
            $table->timestamps();
            $table->unique(array('faq_id','locale'));
            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');

        });
		
	}
	
  public function down()
    {
    	Schema::drop('faq_category_translations');
    	Schema::drop('faq_translations');
	Schema::drop('faqs_categories');
        Schema::drop('faqs');
    }
}