<?php
namespace App\PiplModules\contactrequest\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model 
{

    
	protected $fillable = ['contacted_by','contact_subject','contact_request_category','contact_message','contact_attachment','contact_name','contact_email','contact_phone','reference_no'];
	
	protected $casts = [
        'contact_attachment' => 'array',
    ];
	
	public function category()
	{
		return $this->hasOne('App\PiplModules\ContactRequest\Models\ContactRequestCategory','id','contact_request_category');
	}
	
	public function replies()
	{
		return $this->hasMany('App\PiplModules\ContactRequest\Models\ContactRequestReply','contact_request_id','id');
	}
}