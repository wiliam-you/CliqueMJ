<?php
namespace App\PiplModules\zone\Models;

use Illuminate\Database\Eloquent\Model;

class ClusterDispencery extends Model
{
	
	protected $fillable = ['cluster_id','dispencery_id'];

    public function cluster()
    {
        return $this->belongsTo('App\PiplModules\zone\Models\Cluster','cluster_id','id');
    }

    public function dispencery()
    {
        return $this->belongsTo('App\User','dispencery_id','id');
    }
}