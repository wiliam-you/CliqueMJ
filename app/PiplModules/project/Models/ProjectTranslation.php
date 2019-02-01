<?php
namespace App\PiplModules\project\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model 
{
	protected $fillable = ['title','short_description','description'];
}