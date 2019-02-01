<?php
namespace App\PiplModules\genericmessages\Models;

use Illuminate\Database\Eloquent\Model;

class GenericMessage extends Model
{

    protected $fillable = ['message','view_count'];

}