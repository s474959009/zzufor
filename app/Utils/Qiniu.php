<?php 
namespace App\Utils;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use App\Utils\Predis;

class Qiniu
{ 
 
    protected $bucket = 'lostfound';
    protected $accessKey = '0CVLKTXLUwkHcqki4iEKiVa0NkPaeTcF7jfr6dxx';
    protected $secretKey = '2xjMJ6_v1ClKuumWoJ34lsjZ9nRsZ2Wgc6KlVryN';

    //上传token
    public function getUpToken()
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        
        $upToken = $auth->uploadToken($this->bucket);
        return $upToken;
    }

    //下载token
    public function getDownToken()
    {
        return ;
    }

    //保存微信服务器图片
    public function saveWechatPhoto($openId, $urls)
    {
        //删除微信拉取的链接
        Predis::delPhotoKey($openId); 

        //多个图片保存，没有保存默认图片
        if(is_array($urls))
        {
            foreach($urls as $val)
            {
                $this->initUpload($openId, $val);
            }
        } else {
            $this->initUpload($openId, $urls);
        }      
    }

    //初始化上传
    public function initUpload($openId, $url)
    {
        //上传所需参数
        $fetch = \Qiniu\base64_urlSafeEncode($url);
        $to = \Qiniu\base64_urlSafeEncode($this->bucket);
        $url = 'http://iovip.qbox.me/fetch/'.$fetch.'/to/'.$to;
        $access_token = $this->generate_access_token($this->accessKey, $this->secretKey, $url);
        //curl头信息
        $header[] = 'Content-Type: application/json';
        $header[] = 'Authorization: QBox '. $access_token;
        $con = $this->send('iovip.qbox.me/fetch/'.$fetch.'/to/'.$to, $header);
        //获取上传成功的图片地址，重新存入redis
        $photo = json_decode($con)->key;
        Predis::setPhotoKey($openId, $photo);
    }

    //curl发送请求
    public function send($url, $header) 
    { 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_HEADER,0); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($curl, CURLOPT_POST, 1); 
 
        $con = curl_exec($curl); 
        if ($con === false) 
        { 
            return  false;
        } else { 
            return $con; 
        } 
    }

    //签名运算
    public function generate_access_token($access_key, $secret_key, $url, $params = '')
    { 
        $parsed_url = parse_url($url); 
        $path = $parsed_url['path']; 
        $access = $path; 
        if (isset($parsed_url['query'])) 
        { 
            $access .= "?" . $parsed_url['query']; 
        } 
        $access .= "\n"; 
        if($params)
        { 
            if (is_array($params))  
            { 
                $params = http_build_query($params); 
            } 
        $access .= $params; 
        } 
        $digest = hash_hmac('sha1', $access, $secret_key, true); 
        return $access_key.':'. \Qiniu\base64_urlSafeEncode($digest); 
    }
} 
