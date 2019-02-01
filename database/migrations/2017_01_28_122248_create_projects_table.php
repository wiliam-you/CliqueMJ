<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('project_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug')->unique();
            $table->integer('created_by')->unsigned()->index()->nullable();
            NestedSet::columns($table);
          //  $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
        
        Schema::create('project_category_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('project_category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(array('project_category_id','locale'));
            $table->foreign('project_category_id')->references('id')->on('project_categories')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('project_category_skills', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('project_category_id')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
           // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->foreign('project_category_id')->references('id')->on('project_categories')->onDelete('cascade')->onUpdate('set null');
            $table->timestamps();

        });

         Schema::create('project_category_skill_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('project_category_skill_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description');
            $table->unique(array('project_category_skill_id','locale'),'skill_local_uni');
            $table->foreign('project_category_skill_id','skill_fk')->references('id')->on('project_category_skills')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('short_description');
            $table->text('description');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('project_category_id')->unsigned()->index()->nullable();
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status',array('A','P','B','C','D','R', 'E', 'T'))->comment('A=Active, P=Pending For Approval, B=Blocked, C=Completed, D=Draft, R=Rejected, E=Expired, T=Trashed / Deleted');
            $table->enum('is_featured',array('Y','N'))->default('N');
            $table->string('currency')->nullable();
            $table->text('tags')->nullable();
            $table->integer('parent_project')->unsigned()->nullable();
            $table->float('budget_min')->nullable();
            $table->float('budget_max')->nullable();
            $table->string('budget_type')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->string('project_location')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->string('zipcode')->nullable();
            $table->timestamps();
          //  $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->foreign('project_category_id')->references('id')->on('project_categories')->onDelete('set null')->onUpdate('set null');
        });

           Schema::create('project_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');
            $table->text('short_description');
           // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            $table->timestamps();
        });
        Schema::create('project_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->enum('document_type',array('image','video', 'document'))->nullable();
            $table->boolean('is_default_pic')->default(false);
            $table->string("name")->nullable();
            $table->text("description")->nullable();
            $table->string("path")->nullable();
            $table->timestamps();
          //  $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null')->onUpdate('set null');
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
        Schema.drop("project_documents");
        Schema.drop("projects");
    }
}
