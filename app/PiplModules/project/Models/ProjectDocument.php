<?php
namespace App\PiplModules\project\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
class ProjectDocument extends Model 
{

//	use \Dimsav\Translatable\Translatable, NodeTrait;
//	
//	public $useTranslationFallback = true;
//	public $translatedAttributes = ['description'];
	protected $fillable = ['name','description','path','project_id','document_type'];

	public function projects()
	{
		return $this->belongsTo('App\PiplModules\project\Models\Project','project_id','id');
	}
}