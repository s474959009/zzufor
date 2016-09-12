<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'phone',
        'qq',
        'wechat',
        'salt',
        'avatar',
        'department',
        'major',
        'year',
        'delete_at',
    ];

    protected $dates = ['delete_at'];    

    protected $hidden = ['password', 'remember_token'];

    //一对多获取物品
    public function goods()
    {
        return $this->hasMany('App\Model\Goods', 'userId', 'id');
    }

    /**
     * 生成Salt.
     *
     * @return string Salt.
     */
    public static function generateSalt()
    {
        $length = config('lost.salt_length');
        $randString = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt = $salt.$randString[rand(0, 61)];
        }
        return $salt;
    }

    /**
     * 根据email获取salt
     * @param $email
     * @return mixed
     */
    public static function getSalt($email)
    {
        $salt = self::where('email', $email)->pluck('salt');

        return $salt;
    }




}
