<?php
namespace App\PiplModules\project\Models;

use Illuminate\Database\Eloquent\Model;


class ProjectCategorySkill extends Model 
{

	use \Dimsav\Translatable\Translatable;
	
	public $useTranslationFallback = true;
	
	public $translatedAttributes = ['name','description'];
    
	protected $fillable = ['project_category_id'];
	
	public function category()
	{
		return $this->belongsTo('App\PiplModules\project\Models\ProjectCategory','project_category_id','id');
	}
	
}