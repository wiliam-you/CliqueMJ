<?php
namespace App\PiplModules\webservice\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAdvertisementOffer extends Model
{
	
	protected $fillable = [];

	public function advertisement()
    {
        return $this->belongsTo('App\PiplModules\advertisement\Models\Advertisement','advertisement_id','id');
    }
	
}