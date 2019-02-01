<?php
namespace App\PiplModules\project\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
class ProjectCategory extends Model 
{

	use \Dimsav\Translatable\Translatable, NodeTrait;
	
	public $useTranslationFallback = true;
	
	public $translatedAttributes = ['name'];
    
	protected $fillable = ['name','created_by','parent_id','slug'];
	
	public function parentCat()
	{
		return $this->belongsTo('App\PiplModules\project\Models\ProjectCategory','parent_id','id');
	}
	public function projects()
	{
		return $this->hasMany('App\PiplModules\project\Models\Project','project_category_id');
	}
}