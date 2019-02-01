<?php
namespace App\PiplModules\zone\Models;

use App\PiplModules\coupon\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
	
	protected $fillable = ['title','limit','day','zone_id'];

    public function clusterDispencery()
    {
        return $this->hasMany('App\PiplModules\zone\Models\ClusterDispencery','cluster_id','id');
    }

    public function zone()
    {
        return $this->belongsTo('App\PiplModules\zone\Models\Zone','zone_id','id');
    }

    public function coupons()
    {
        return $this->hasMany('App\PiplModules\coupon\Models\Coupon','cluster_id','id');
    }

    public function getAvailableCoupon()
    {
        return $this->hasOne('App\PiplModules\coupon\Models\Coupon','cluster_id','id')->where('is_expire','0');
    }

    public function activeCoupon($id)
    {
//        return $coupon_count = Coupon::where('cluster_id',$id)->where('start_date','<=', Carbon::now()->format('m/d/Y'))->where('end_date','>=', Carbon::now()->format('m/d/Y'))->count();
        $coupon = Coupon::where('cluster_id',$id)->where('is_expire','0')->first();
        if($coupon=='')
        {
            return 0;
        }
        else
        {
            return 1;
        }

    }
}