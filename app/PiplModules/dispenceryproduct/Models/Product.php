<?php
namespace App\PiplModules\dispenceryproduct\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{
	
	protected $fillable = ['name','user_id','property_id','price','quantity','status','image','description'];

    public function property()
    {
        return $this->belongsTo('App\PiplModules\property\Models\Property','property_id','id');
    }

}