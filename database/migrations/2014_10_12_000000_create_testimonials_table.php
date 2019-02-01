<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     * This file is use to create testimonial table
     * @return void
     */
    public function up()
    {
		
        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('user_description');
            $table->text('photo');
            $table->text('description');
            $table->enum('status',[0,1]);
	    $table->integer('created_by');
            $table->timestamps();
        });
		
     }
	
    public function down()
    {
        
    }
}