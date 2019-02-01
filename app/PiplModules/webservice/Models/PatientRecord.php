<?php
namespace App\PiplModules\webservice\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
	
	protected $fillable = ['offer_id','sender','receiver'];

//	protected $with = ['offer'];

	public function userInformation()
    {
        return $this->belongsTo('App\UserInformation','patient_id','user_id');
    }

    public function offer()
    {
        return $this->belongsTo('App\PiplModules\advertisement\Models\Advertisement','offer_id','id');
    }
	
}