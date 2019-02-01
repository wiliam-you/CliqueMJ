<?php
namespace App\PiplModules\zone\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
	
	protected $fillable = ['title'];

    public function clusters()
    {
        return $this->hasMany('App\PiplModules\zone\Models\Cluster','zone_id','id');
    }
}