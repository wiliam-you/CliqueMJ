<?php
namespace App\PiplModules\report\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	
	protected $fillable = [];

	public function dispensary()
    {
        return $this->belongsTo('App\UserInformation','dispensary_id','user_id');
    }
	
    public function patient()
    {
        return $this->belongsTo('App\UserInformation','patient_id','user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\PiplModules\product\Models\Product','product_id','id');
    }

    public function getTotal()
    {
        dd(234);
    }
}