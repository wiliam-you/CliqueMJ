<?php
namespace App\PiplModules\advertisementbrand\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementBrand extends Model
{
	
	protected $fillable = ['name'];

	public function advertisement()
    {
        return $this->hasMany('App\PiplModules\advertisement\Models\Advertisement','brand_id','id')->where('is_delete',0);
    }
}