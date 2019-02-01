<?php
namespace App\PiplModules\feedback\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	
	protected $fillable = ['feedback','dispencery_id'];

	public function user()
    {
        return $this->belongsTo('App\User','patient_id','id');
    }
	
}