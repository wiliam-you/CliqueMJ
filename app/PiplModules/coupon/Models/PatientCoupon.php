<?php
namespace App\PiplModules\coupon\Models;

use Illuminate\Database\Eloquent\Model;

class PatientCoupon extends Model
{
	
	protected $fillable = ['code','coupon_value','coupon_type','property_id','dispensary_id'];


	public function coupon()
    {
        return $this->belongsTo('App\PiplModules\coupon\Models\Coupon','coupon_id','id');
    }

    public function userInformation()
    {
        return $this->belongsTo('App\UserInformation','to_dispensary_id','user_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','to_dispensary_id','id');
    }
}