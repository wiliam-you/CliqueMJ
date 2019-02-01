<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalSettingsTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
        Schema::create('global_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('value');
            $table->text('validate');
            $table->integer('lang_id');
            $table->string('slug')->unique();
            $table->timestamps();
        });	
    }
	
  public function down()
    {
        Schema::drop('global_settings');
    }
}