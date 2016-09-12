<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RestRequest;
use App\Http\Requests\ForgetRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AuthRequest;
use App\Utils\SendMail;

use Auth,Redirect;
use App\Model\User_Lost;
use App\Model\User_Wechat as Wechat;
use App\Model\Feedback;
use App\Model\Password;

class AuthController extends Controller
{

    public function getLogin(Request $request)
    {
       
        //已登录用户返回首页
        if(Auth::check()){
        
            return Redirect('/lost');

        }
 
        //从session获取跳转url
        $url = $request->session()->get('_previous')['url'];
        
        return view('user.login')
                ->with(["url"=>$url]);
    }


    /**
     * 登录
     * @param LoginRequest $request
     * @return array|string
     */
    public function postLogin(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $salt = User_Lost::getSalt($email);

        if(Auth::attempt(array('email' => $email, 'password' => $password.$salt)))
        {

           return "success"; 

        } else {

            return array('err' => ['密码错误']);
        }


    }

    public function getRegister()
    {
        return view('user.register');
    }

    /**
     * 注册
     * @param RegisterRequest $request
     * @return array|string
     */
    public function postRegister(RegisterRequest $request)
    {
        $data = $request->all();

        $password = $data['password'];
        $data['salt'] = User_Lost::generateSalt();
        $data['password'] = bcrypt($password.$data['salt']);

        if(User_Lost::create($data))
        {
            SendMail::RegisterMail($data['email']);
            return 'success';
        } else {
            return array('err' => ['注册失败']);
        }
    }

    /**
     * 退出
     * @return string
     *
     */
    public function getLogout()
    {
        if(Auth::logout())
        {
            return 'success';
        }
    }

    //忘记密码邮箱验证
    public function getForget()
    {
        return view('auth.forget');
    }

    //验证邮箱发送邮件
    public function postForget(ForgetRequest $request)
    {
        $email = $request->get('email');

        if(Password::tokenExists($email))
        {
            $rest = new Password();

            $token = $rest->createNewToken();

            $rest->email = $email;
            $rest->token = $token;

            if($rest->save())
            {
                SendMail::RestMail($email,$token);
                return 'success';
            } else {
                return array('err'=>['发送重置密码链接失败']);
            }
        } else {
            return array('err'=>['一小时内只能申请一次']);
        }
    }

    //重置密码页面
    public function getRest($token)
    {
        if($token)
        {
            $email = Password::exists($token);
                
            if($email)
            {
                return view('auth.rest')
                    ->with('email',$email);
            } else {
                return view('auth.linkInvalid');
            }
        }

    }

    //重置密码
    public function postRest(RestRequest $request)
    {
        $password = $request->get('password');
        $email= $request->get('email');

        $data['salt'] = User_Lost::generateSalt();
        $data['password'] = bcrypt($password.$data['salt']);

        $user = User_Lost::where('email', $email);

        if($user->update($data))
        {
            return 'success';
        } else {
            return array('err' => ['重置密码失败']);
        }
    }

    //修改密码页面
    public function getModify()
    {
        return view('auth.modify');
    }

    //修改密码
    public function postModify(RestRequest $request)
    {
        $password = $request->get('password');
        $data['salt'] = User_Lost::generateSalt();
        $data['password'] = bcrypt($password.$data['salt']);

        $userId = Auth::user()->id;

        $user = User_Lost::find($userId);


        if($user->update($data))
        {
            return 'success';
        } else {
            return array('err' => ['修改密码失败']);
        }
    }

    //个人中心
    public function getProfile()
    {
        $userId = Auth::user()->id;

        $goods = User_Lost::find($userId)->goods()->take(3)->get()->toArray();
        return view('profile.index')
            ->with('goods',$goods);
    }

    //用户个人信息
    public function getUserProfile()
    {
        return view('profile.profile');
    }

    public function postUserProfile(ProfileRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);

        $userId = Auth::user()->id;

        $user = User_Lost::find($userId);

        if($user->update($data))
        {
            return 'success';
        } else {
            return array('err'=>'更新个人信息失败');
        }
    }

    //学号认证
    public function getAuth()
    {
        return view('profile.auth');
    }

    public function postAuth(AuthRequest $request)
    {
        $studentId  = $request->get('studentId');
        $password = $request->get('password');

        //获取根据学号微信绑定信息
        $wechat = Wechat::where('studentId', $studentId)->first();

        if($wechat['password'] == $password){

            $userId = Auth::user()->id;

            //将微信信息绑定到用户信息
            $user = User_Lost::find($userId);
            $user['major'] = $wechat['major'];
            $user['year'] = $wechat['year'];
            $user['status'] = 1;

            $wechat['lostId'] = $userId;

            if($wechat->save() && $user->save())
            {
                return 'success';
            } else {
                return array('err'=>['认证失败']);
            }
        } else {
            return array('err' => ['密码错误']);
        }
    }

    //意见反馈
    public function getFeedback()
    {
        return view('profile.feedback');
    }

    public function postFeedback(Request $request)
    {
        $data = $request->all();
        $data['userId'] = Auth::user()->id;

        if(Feedback::create($data))
        {
            return 'success';
        } else {
            return array('err' => ['反馈失败']);
        }
    }

    //发布物品管理
    public function getGoods()
    {
        $userId = Auth::user()->id;

        $goods = User_Lost::find($userId)->goods()->simplePaginate(4);

        return view('profile.goods')
                ->with(['goods' => $goods]);
    }

}
