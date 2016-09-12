<?php namespace App\Utils;

use Carbon\Carbon;
use LRedis;

class Redis
{  
    

    //设置用户发布图片临时key
    public function setPhotoKey($openId, $url)
    {
        $key = $openId.':photos';
        $num =  LRedis::sadd($key, $url);
        //设置10分钟失效
        LRedis::expire($key, 600);
        return $num;
    }
    
    //获取用户发布的图片
    public function getPhotoKey($openId)
    {
        $key = $openId.':photos';
        $photos = LRedis::smembers($key);
        if($photos == null)
        {
            return array("http://7xpq7s.com1.z0.glb.clouddn.com/Fq13A_RGknAMh5nR3UNj1kr8Lmvp");
        } else {
            return $photos;
        }
    }

    //删除用户临时key
    public function delPhotoKey($openId)
    {
        $key = $openId.':photos';
       
        return  LRedis::del($key);
       
        
    }    

    //返回当前图片集合数量
    public function getPhotoNum($openId)
    {
        $key = $openId.':photos';
        return LRedis::scard($key);
    }

} 
