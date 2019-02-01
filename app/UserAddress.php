<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    
    protected $fillable = ['user_id','addressline1','addressline2','user_country','user_state','user_city','suburb','user_custom_city','zipcode'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
	
}
