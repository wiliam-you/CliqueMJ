<?php
namespace App\PiplModules\roles\Models;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $timestamps  = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [ 'role_id', 'user_id','created_at','updated_at'];
    protected $table='role_user';

    /**
     * Create a new model instance.
     *
     * @param array $attributes
     * @return void
     */
    
//    public function __construct(array $attributes = [])
//    {
//        parent::__construct($attributes);
//
//        if ($connection = config('roles.connection')) {
//            $this->connection = $connection;
//        }
//    }
//	
	
}
