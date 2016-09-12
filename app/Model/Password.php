<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Password extends Model
{
    protected $table = 'password_resets';


    //生成TOKEN
    public function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), $this->hashKey);
    }

    /**
     * token是否过期
     * @param $token
     * @return bool true过期 false不过期
     */
    public static function tokenExpired($token)
    {
        $expirationTime = strtotime($token['created_at'])+3600;

        return $expirationTime < self::getCurrentTime();
    }

    /**
     * 根据邮箱判断该用户是否在1小时内收到过链接
     * @param $email
     * @return bool
     */
    public static function tokenExists($email)
    {
        $token = self::where('email', $email)->orderBy('created_at', 'DESC')->first();

        return !$token || self::tokenExpired($token);
    }

    //获取当前时间
    protected static function getCurrentTime()
    {
        return time();
    }

    //判断当前token是否存在
    public static function exists($token)
    {
        $email = self::where('token',$token)->first()->pluck('email');
        $token = self::where('email', $email)->orderBy('created_at', 'DESC')->first();

        if($token && ! self::tokenExpired($token))
        {
            return $email;
        } else {
            return false;
        }
    }




}
