<?php
namespace App\PiplModules\webservice\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementOfferReport extends Model
{
	
	protected $fillable = [];

	public function advertisement()
    {
        return $this->belongsTo('App\PiplModules\advertisement\Models\Advertisement','offer_id','id');
    }

    public function scanBy()
    {
        return $this->belongsTo('App\UserInformation','scan_by','user_id');
    }

    public function redeemBy()
    {
        return $this->belongsTo('App\UserInformation','redeem_by','user_id');
    }
	
}