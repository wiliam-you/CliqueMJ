<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;
class CreateCategoriessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by')->unsigned()->index()->nullable();
            NestedSet::columns($table);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
        
        Schema::create('category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(array('category_id','locale'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categoies');
    }
}
