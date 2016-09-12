<?php
namespace App\Utils;

use Mail;

class SendMail
{
    public static function RegisterMail($email)
    {
        $flag = Mail::send('emails.register',['name'=>'八点一刻'],function($message) use($email){
            $message->to($email)->subject('八点一刻-注册成功');
        });

        if($flag){
            return 'success';
        }else{
            return array('err' => ['邮件发送失败']);
        }
    }

    public static function RestMail($email, $token)
    {
        $flag = Mail::send('emails.rest',['token'=>$token],function($message) use($email){
            $message->to($email)->subject('八点一刻-重置密码');
        });

        if($flag){
            return 'success';
        }else{
            return array('err' => ['邮件发送失败']);
        }
    }
}