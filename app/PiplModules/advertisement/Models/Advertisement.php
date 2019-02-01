<?php
namespace App\PiplModules\advertisement\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
	
	protected $fillable = ['is_mj_offer','unique_code','zone_id','brand_id','status','offer','limit','photo','start_date','end_date'];

	public function brand()
    {
        return $this->belongsTo('App\PiplModules\advertisementbrand\Models\AdvertisementBrand','brand_id','id');
    }
}