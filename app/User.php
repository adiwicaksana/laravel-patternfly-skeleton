<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['full_name', 'username', 'email', 'role_id', 'password', 'is_suspended', 'is_disabled', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the role record associated with the user.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public static function authorize($permission)
    {
        $enable_rbac = config('rbac.enable');
        $user_role_name = Role::find(\Auth::user()->role_id)->name;
        $role_permissions = config('rbac.'.$user_role_name);

        $role_permissions[] = 'home';

        if ($enable_rbac) {
            if (! in_array($permission, $role_permissions)) {
                return false;
            }
        }
        return true;
    }
}
