<?php
namespace App\PiplModules\project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Kalnoy\Nestedset\NodeTrait;
class Project extends Model 
{

  
  use \Dimsav\Translatable\Translatable;
	
	public $useTranslationFallback = true;
	
	public $translatedAttributes = ['title','short_description','description'];
	protected $fillable = ['title','short_description','description','created_by','project_category_id','start_date','end_date','status','is_featured','currency','tags','parent_project','budget_min','budget_max','budget_type','slug','code','created_by','project_location','country_id','state_id','city_id','zipcode'];
	
	
	protected $casts = [
        'tags' => 'array',
    ];
    
	public function category()
	{
		return $this->hasOne('App\PiplModules\project\Models\ProjectCategory','id','project_category_id');
	}
    
	public function projectDocuments()
	{
		return $this->hasOne('App\PiplModules\project\Models\ProjectDocument','project_id','id');
	}
	
}