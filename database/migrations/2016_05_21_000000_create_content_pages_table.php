<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentPagesTable extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
       Schema::create('content_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page_alias');
            $table->enum('page_status',array(0,1));
            $table->integer('created_by');
            $table->timestamps();
        });
        
	Schema::create('content_page_translations', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('page_title');
            $table->text('page_content');
            $table->string('page_seo_title');
            $table->text('page_meta_keywords');
            $table->text('page_meta_descriptions');
            $table->string('locale')->index();
            $table->integer('content_page_id')->unsigned()->index();
            $table->unique(array('content_page_id','locale'));
            $table->foreign('content_page_id')->references('id')->on('content_pages')->onDelete('cascade');
	    $table->timestamps();
        });
     }
	
  public function down()
    {
	Schema::drop('content_page_translations');
        Schema::drop('content_pages');
    }
}