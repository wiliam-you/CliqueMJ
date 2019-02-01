<?php
namespace App\PiplModules\coupon\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
	
	protected $fillable = ['code','coupon_value','coupon_type','dispensary_id','cluster_id','unique_code','status'];

	public function property()
    {
        return $this->belongsTo('App\PiplModules\property\Models\Property','property_id','id');
    }

    public function dispencery()
    {
        return $this->belongsTo('App\User','dispensary_id','id');
    }

    public function patientCoupons()
    {
        return $this->hasMany('App\PiplModules\coupon\Models\PatientCoupon','coupon_id','id');
    }

    public function cluster()
    {
        return $this->belongsTo('App\PiplModules\zone\Models\Cluster','cluster_id','id');
    }
}