<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * This file has user table, Userinformation table, User Address table Country, State,City Table creation code
     *
     * @return void
     */
    public function up()
    {
	
        //user main Table
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
	
        //country table
	Schema::create('countries', function (Blueprint $table) {
         $table->increments('id');
         $table->timestamps();
			
        });
	
         //country translation table
	Schema::create('country_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('country_id')->unsigned();
                $table->string('name');
                $table->string('locale')->index();
                $table->unique(array('country_id','locale'));
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
                $table->timestamps();
			
        });
		
	Schema::create('states', function (Blueprint $table) {
                $table->increments('id');
		$table->integer('country_id')->index()->unsigned();
         	$table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
                $table->timestamps();
			
        });
		
	Schema::create('state_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('state_id')->unsigned();
		$table->string('name');
		$table->string('locale')->index();
		$table->unique(array('state_id','locale'));
   		$table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
                $table->timestamps();
			
        });
		
	Schema::create('cities', function (Blueprint $table) {
        $table->increments('id');
	$table->integer('state_id')->index()->unsigned();
        $table->integer('country_id')->unsigned();
        $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        $table->timestamps();
        });
		
	Schema::create('city_translations', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('city_id')->unsigned();
	$table->string('name');
	$table->string('locale')->index();
	$table->unique(array('city_id','locale'));
   	$table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        $table->timestamps();
			
        });
		
		
	Schema::create('user_informations', function (Blueprint $table) {
	$table->increments('id');
        $table->integer('user_id')->unique()->unsigned();
        $table->string('profile_picture');
	$table->enum('gender',array(1, 2, 3));
	$table->string('activation_code');
	$table->string('facebook_id');
	$table->string('twitter_id');
	$table->string('google_id');
        $table->string('linkedin_id');
        $table->string('pintrest_id');
        $table->string('user_birth_date');
        $table->string('first_name');
        $table->string('last_name');
        $table->string('user_phone');
        $table->string('user_mobile');
        $table->enum('user_type',array(1, 2, 3));
        $table->text('about_me');
        $table->enum('user_status',array(0,1, 2));
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        $table->timestamps();
        });
		
	Schema::create('user_addresses', function (Blueprint $table) {
	$table->increments('id');
        $table->integer('user_id')->unique()->unsigned();
        $table->text('address1');
	$table->text('address2');
	$table->integer('user_country')->unsigned()->index()->nullable();
	$table->integer('user_state')->unsigned()->index()->nullable();
        $table->integer('user_city')->unsigned()->index()->nullable();
        $table->string('user_custom_city');
        $table->string('zipcode');
        $table->string('suburb');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        $table->foreign('user_country')->references('id')->on('countries')->onDelete('set null')->onUpdate('set null');
        $table->foreign('user_state')->references('id')->on('states')->onDelete('set null')->onUpdate('set null');
        $table->foreign('user_city')->references('id')->on('cities')->onDelete('set null')->onUpdate('set null');
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
        Schema::drop('user_informations');
		Schema::drop('users');
    }
}
