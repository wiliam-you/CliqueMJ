<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateBlogsTable extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
            Schema::create('post_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->integer('created_by')->unsigned()->index()->nullable();
            NestedSet::columns($table);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
		
            Schema::create('post_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(array('post_category_id','locale'));
            $table->foreign('post_category_id')->references('id')->on('post_categories')->onDelete('cascade');
            $table->timestamps();
        });
		
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('post_category_id')->unsigned()->index()->nullable();
            $table->string('post_url')->unique();
            $table->string('post_image');
            $table->enum('allow_comments',array('0','1'));
            $table->enum('allow_attachments_in_comments',array('0','1'));
            $table->enum('post_status',array('0','1'));
            $table->text('post_attachments');
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
			
        });
		
		
           Schema::create('post_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('short_description');
            $table->text('description');
            $table->string('locale')->index();
            $table->integer('post_id')->unsigned()->index();
            $table->string('seo_title');
            $table->text('seo_keywords');
            $table->text('seo_description');
            $table->timestamps();
            $table->unique(array('post_id','locale'));
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

        });
		
           Schema::create('post_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment');
            $table->text('comment_attachments');
            $table->integer('commented_by')->index();
            $table->integer('post_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

        });
		
          Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();

        });
		
         Schema::create('post_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->index();
            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

        });
		
        }
	
  public function down()
    {
    	Schema::drop('post_category_translations');
    	Schema::drop('post_translations');
        Schema::drop('post_categories');
        Schema::drop('post_comments');
        Schema::drop('posts');
    }
}