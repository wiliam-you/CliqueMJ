<?php
namespace App;
use App\PiplModules\feedback\Models\Feedback;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\PiplModules\roles\Traits\HasRoleAndPermission;
use App\PiplModules\roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,HasRoleAndPermissionContract
{
    use Authenticatable,  CanResetPassword,HasRoleAndPermission;
	
	   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function userInformation()
    {
          return $this->hasOne('App\UserInformation');
    }
    public function userAddress()
    {
          return $this->hasMany('App\UserAddress');
    }	
    
    /**
     * Set the password to be hashed when saved
     */
    public function setPasswordAttribute($password)
    {
            $this->attributes['password'] = \Hash::make($password);
    }
    public function getRememberToken()
    {   
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
         $this->remember_token = $value;
    }   

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function clusterDispencery()
    {
        return $this->hasOne('App\PiplModules\zone\Models\ClusterDispencery','dispencery_id','id');
    }

    public function feedbackDispencery()
    {
        return $this->hasMany('App\PiplModules\feedback\Models\Feedback','dispencery_id','id');
    }

    public function productCount()
    {
        return $this->hasMany('App\PiplModules\dispenceryproduct\Models\Product','user_id','id')->where('is_delete',0)->count();
    }

    public function customerCount()
    {
        return $this->hasMany('App\PiplModules\report\Models\Report','dispensary_id','id')->count();
    }

    public function getCountOfNewFeedback()
    {
        return $total_feedback = Feedback::where('is_read',0)->count();
    }

    public function getCountOfAllFeedback()
    {
        return $total_feedback = Feedback::all();
    }

    public function getCountOfDispensaryNewFeedback()
    {
        return $this->hasMany('App\PiplModules\feedback\Models\Feedback','dispencery_id','id')->where('is_read',0);
    }

    public function getFeedbackOfDispensary()
    {
        return $this->hasMany('App\PiplModules\feedback\Models\Feedback','dispencery_id','id')->where('is_dispensary_read',0);
    }

    public function totalFeedbackOfDispensary()
    {
        return $this->hasMany('App\PiplModules\feedback\Models\Feedback','dispencery_id','id');
    }

}