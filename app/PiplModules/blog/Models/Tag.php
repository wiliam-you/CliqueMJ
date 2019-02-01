<?php
namespace App\PiplModules\blog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model 
{
    protected $fillable = array('name','slug');
	
	public function posts()
    {
         return $this->belongsToMany('App\PiplModules\blog\Models\Post');
    }
	
}