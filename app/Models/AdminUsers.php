<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminUsers extends Authenticatable implements JWTSubject
{
	use HasRoles;
	use Notifiable;

	public $timestamps = false; //添加禁用时间戳

	protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password','create_time','last_login_time','last_login_ip','introduction','facephoto','nickname','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

	// Rest omitted for brevity

	/**
	* Get the identifier that will be stored in the subject claim of the JWT.
	*
	* @return mixed
	*/
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	* Return a key value array, containing any custom claims to be added to the JWT.
	*
	* @return array
	*/
	public function getJWTCustomClaims()
	{
		return [];
	}

	/**
	 * 内联创建者对象
	 */
	public function adminUsers()
    {
    	return $this->belongsTo('App\Models\AdminUsers','create_uid');
    }

	public static function create(array $attributes = [],array $roles = [])
    {
		if(!Roles::rolesInUsers($roles)){ //检测提交的赋予权限是否是可赋予的  有没有越权
			throw new \App\Exceptions\Admin\CustomException(21006);
		}
		$static = new static();
		$uid = Auth::id();
		$users = DB::transaction(function () use($static,$attributes,$roles,$uid) {
            if($static::where('username',$attributes['username'])->first()){
            	throw new \App\Exceptions\Admin\CustomException(21007); //用户名存在
            }
            $users = static::query()->create($attributes);
            $users->assignRole($roles); //向用户注入多个权限
            return $users;
        });
        return $users;
    }

    public static function saveById(array $attributes = [],array $roles = [])
    {
    	if(!Roles::rolesInUsers($roles)){ //检测提交的赋予权限是否是可赋予的  有没有越权
			throw new \App\Exceptions\Admin\CustomException(21006);
		}
    	$static = new static();
        $uid = Auth::id();
        $users = DB::transaction(function () use($static,$attributes,$roles,$uid) {
        	$id = $attributes['id'];
            unset($attributes['id']);
        	if($static::where('username',$attributes['username'])->where('id','!=',$id)->first()){
            	throw new \App\Exceptions\Admin\CustomException(21007); //用户名存在
            }
            $usersCollect = $static::find($id);
            if($usersCollect->create_uid === $uid || $uid === 1){ //创建者与主账号均有权利修改
                $users = $static::where('id',$id)->update($attributes);
                $usersCollect->syncRoles($roles); //撤销并刷新角色
                return $usersCollect;
            }else{
                throw new \App\Exceptions\Admin\CustomException(21004);
                
            }
        });
        
        return $users;
    }
}
