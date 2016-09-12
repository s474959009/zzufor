<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_Wechat extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql-auth';    

    protected $table = 'users';

    protected $primaryKey = 'openId';

    protected $dates = ['deleted_at'];    

    public function grade()
    {
        return $this->hasMany('App\Model\Grade',  'userId', 'id');
    }   

    //根据openId获取用户Id
    public function getIdByOpenid($openId)
    {
        $user = $this->where('openId', $openId)->first();
        if($user)
        {
            $userId = $user->id;
        } else {
            return false;
        }   
    }
}
