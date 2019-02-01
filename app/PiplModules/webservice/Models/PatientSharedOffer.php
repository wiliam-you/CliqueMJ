<?php
namespace App\PiplModules\webservice\Models;

use Illuminate\Database\Eloquent\Model;

class PatientSharedOffer extends Model
{
	
	protected $fillable = ['offer_id','sender','receiver'];

	public function advertisement()
    {
        return $this->belongsTo('App\PiplModules\advertisement\Models\Advertisement','offer_id','id');
    }

    public function senderInfo()
    {
        return $this->belongsTo('App\UserInformation','sender','user_id');
    }

    public function receiverInfo()
    {
        return $this->belongsTo('App\UserInformation','receiver','user_id');
    }

    public function advertiseInfo()
    {
        return $this->belongsTo('App\PiplModules\advertisement\Models\Advertisement','offer_id','id');
    }
	
}