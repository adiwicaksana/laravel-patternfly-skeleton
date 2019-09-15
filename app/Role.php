<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    /**
     * Get the users record associated with the user.
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
