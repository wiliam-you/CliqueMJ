<?php
namespace App\PiplModules\chooseussection\Models;

use Illuminate\Database\Eloquent\Model;

class ChooseUs extends Model
{
	protected  $table = 'choose_us';
	protected $fillable = ['title','description'];
	
}