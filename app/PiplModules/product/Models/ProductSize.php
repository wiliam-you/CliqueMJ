<?php
namespace App\PiplModules\product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
	
	protected $fillable = ['name','user_id','property_id','status','image','description'];

    public function property()
    {
        return $this->belongsTo('App\PiplModules\property\Models\Property','property_id','id');
    }
    public function product()
    {
        return $this->belongsTo('App\PiplModules\product\Models\Product','product_id','id');
    }
}