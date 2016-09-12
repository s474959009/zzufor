<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller\ValidatesRequests;

use App\Http\Requests;
use App\Http\Requests\CreateBindRequest;
use App\Http\Requests\ModifyBindRequest;
use App\Http\Controllers\Controller;

use App\Model\User_Wechat;

use App\Utils\Grab;


class UserController extends Controller
{

    //绑定页面
    public function getBind($openId)
    {        
        return view('bind.bind',['openId'=> "$openId"]);
        
    }   

    public function postBind(CreateBindRequest $request)
    {
        $studentId = $request->get('studentId');
        $password = $request->get('password');    
        
        //获取详细用户信息
        $client = new Grab();
        $userInfo = $client->getInfo($studentId, $password);
            
        if($userInfo == 'err')
        {
            $err = array('net'=>['校务网网络繁忙，请稍后再试']);
            //校务网网络故障
            return $err;
        }
    
        if($userInfo == "")
        {   
            $err = array('auth'=>['学号或密码错误，请到校务网确认']);
            //账号密码错误
            return $err;
        }        

        $user = new User_Wechat();
        $user->openId = $request->get('openId');
        $user->studentId = $studentId;
        $user->password = $password;
        $user->userName = $userInfo['userName'];
        $user->major = $userInfo['major'];
        $user->year = substr($studentId, 0 ,4);
        if($user->save())
        {
            return 'success';
        } else {
            return array('err' => ['绑定失败']);
        }
    }

    //修改绑定    
    public function getModify($openId)
    {
        
        return view('bind.editBind',['openId'=> "$openId"]);
    }


     public function postModify(ModifyBindRequest $request)
    {
        $studentId = $request->get('studentId');
        $password = $request->get('password');    
        
        //获取详细用户信息
        $client = new Grab;
        $userInfo = $client->getInfo($studentId, $password);
            
        if($userInfo == 'err')
        {
            $err = array('net'=>['校务网网络繁忙，请稍后再试']);
            //校务网网络故障
            return $err;
        }
    
        if($userInfo == "")
        {   
            $err = array('auth'=>['学号或密码错误，请到校务网确认']);
            //账号密码错误
            return $err;
        }        

        //根据openId获取用户 并修改信息
        $openId = $request->get('openId');
        $user = User_Wechat::withTrashed()->where('openId',$openId)->first();
        //$user = User_Wechat::find($openId);
        //当前用户存在，老用户存在，删除当前用户成绩，将老用户成绩改为当前用户的并删除老用户
        if($user)
        {
            $user->grade()->delete();
            $owner=User_Wechat::where('studentId', $studentId)->first();
            if($owner)
            {
                $owner->grade()->update(['userId' => $user->id]);
                $owner->forceDelete();
            }   
        //当前用户不存在，若老用户存在则替换用户信息，若老用户不存在则新建用户                 
        } else {
            $owner=User_Wechat::where('studentId', $studentId)->first();
            if($owner)
            {    
                $user = $owner;
            } else {
                $user = new User_Wechat();
            }
        }
        
        $user->openId = $openId;
        $user->studentId = $studentId;
        $user->password = $password;
        $user->userName = $userInfo['userName'];
        $user->major = $userInfo['major'];
        $user->year = substr($studentId, 0 ,4);
        if($user->save())
        {
            return 'success';
        } else {
            array('err' => ['修改绑定失败']);
        }
    }

    public function unbind($openId)
    {
        $openId = $request->get('openId');

        $user = User_Wechat::find($openId);

        $user->delete();    
    }
}
