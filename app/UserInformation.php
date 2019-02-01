<?php
namespace App;
use App\PiplModules\coupon\Models\PatientUseCoupon;
use Illuminate\Database\Eloquent\Model;
use DB;
class UserInformation extends Model
{
    
    protected $fillable = ['dispensary_name','opening_hour','opening_minut','opening','closing_hour','closing_minut','closing','lat','lng','profile_picture','gender','activation_code','facebook_id','twitter_id','google_id','linkedin_id','pintrest_id','user_birth_date','first_name','last_name','user_phone','user_mobile','user_status','user_type','user_id','user_name','address','post_code','city_id','state_id','application_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['facebook_id','twitter_id','google_id','linkedin_id'];
    public function user()
    {
            return $this->belongsTo('App\User');
    }

    public function clusterDispencery()
    {
        return $this->hasOne('App\PiplModules\zone\Models\ClusterDispencery','dispencery_id','user_id');
    }

    public function state()
    {
        return $this->belongsTo('App\PiplModules\admin\Models\State','state_id','id');
    }

    public function city()
    {
        return $this->belongsTo('App\PiplModules\admin\Models\City','city_id','id');
    }

    public static function getByDistance($lat, $lng, $distance)
    {
        $results = DB::select(DB::raw('SELECT * FROM p1127_user_informations WHERE
                        lat BETWEEN ('.$lat.' - ('.$distance.'*0.018)) AND ('.$lat.' + ('.$distance.'*0.018)) AND
                        lng BETWEEN ('.$lng.' - ('.$distance.'*0.018)) AND ('.$lng.' + ('.$distance.'*0.018))') );


        return $results;
    }

    public function getTotalCouponUsedCount()
    {
        return $this->hasMany('App\PiplModules\coupon\Models\PatientUseCoupon','patient_id','user_id');
    }

    public function getTotalOfferRedeem()
    {
        return $this->hasMany('App\PiplModules\webservice\Models\AdvertisementOfferReport','redeem_by','user_id')->get();
    }

    public function getTotalOfferUsedCount()
    {
        return $this->hasMany('App\PiplModules\webservice\Models\PatientAdvertisementOffer','patient_id','user_id');
    }

    public function getTotalFreeGrameCount($id)
    {
        $total_gram = 0;

        $patient_used_coupon = PatientUseCoupon::where('patient_id',$id)->get();
        foreach($patient_used_coupon as $coupon)
        {
            $total_gram += $coupon->offer;
        }

        return $total_gram;
    }
	
}
