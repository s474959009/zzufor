<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use Log;
use App\Http\Requests;
use App\Utils\Grab;
use App\Http\Controllers\Controller;
use App\Utils\Predis;
use App\Utils\Goods;
use App\Utils\Qiniu;
use App\Model\User_Wechat;
use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{
    public function serve(Application $wechat)
    {
         $server = $wechat->server;

         $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    return "暂不支持";
                    break;
                case 'text':
                    $openId = $message->FromUserName;
                    $content = $message->Content;
                    $qiniu = new Qiniu();
                    $user = new User_Wechat();
                    $base_url = config('wechat.base_url');

                    if(mb_strlen($content,'UTF-8') < 5 && preg_match("/成绩/",$content))
                    {
                        $arr_info = explode('绩', $content);
                        $term = $arr_info[1];
                        $grab = new Grab();
                        $reply = $grab->getGrade($openId, $term);
                        if(!$reply)
                        {
                            $reply = '请先<a href="'.$base_url.'bind/create/'.$openId.'">点此绑定'.'</a>学号';
                        }
                        return $reply;

                    } else {

                        switch($content)
                        {
                            case 'token':
                                break;
                            case '绑定':
                                $url = $base_url.'bind/create/'.$openId;
                                $reply = '<a href="'.$url.'">点此绑定学号</a>';
                                break;
                            case '解除绑定':    
                                $grab = new Grab();
                                $reply = $grab->unBind($openId);
                                break;   
                            case '恢复绑定':    
                                $grab = new Grab();
                                $reply = $grab->reBind($openId);
                                break;   
                            case '修改绑定':
                                $url = $base_url.'bind/modify/'.$openId;
                                $reply =  '<a href="'.$url.'">点此修改绑定</a>';
                                break;

                            case '失物招领':
                                $url = $base_url.'lost';
                                $reply = '<a href="'.$url.'">失物招领</a>';
                                break;

                            case '取消':
                                 if(Predis::delPhotoKey($openId))  $reply = '取消发布成功';
                                 else $reply = '取消发布失败';
                                 break;

                            case '校历':
                                $url = $base_url.'calendar';
                                $reply = '<a href="'.$url.'">点击查看校历</a>';
                                break;

                            case '发布':
                                    //将redis中的微信图片保存到七牛并替换
                                    if($urls = Predis::getPhotoKey($openId))  $qiniu->saveWechatPhoto($openId, $urls);
                                    $reply = '<a href="'.$base_url.'lost/goods/add/'.$openId.'">点此发布物品</a>';
                                break
                                ;
                            case 'help':
                                $reply = " 1、查询成绩请回复「绑定」，按要求输入学号密码绑定"."\n"." 2、绑定成功回复「成绩」可查询当前学期成绩。"."\n"." 3、查询其他学期只需在「成绩」后面加上所查学期数，如「成绩1」可查询第一学期成绩。"."\n". " 4、更换绑定学号回复「修改绑定」。"."\n". " 5、解除绑定回复「解除绑定」。"."\n". " 6、恢复绑定回复「恢复绑定」。"."\n"." 7、回复「校历」，查看校历。" ."\n"."       技术支持 @ AlphaThink";
                                break;

                            default:
                                $reply = "回复「help」查看使用说明";
                                break;
                        }
                        return $reply;
                    }
                    break;
                case 'image':
                    $openId = $message->FromUserName;
                    $url = $message->PicUrl;
                    if(Predis::getPhotoNum($openId) < 4)
                    {
                        Predis::setPhotoKey($openId, $url);
                        return "1";
                    } else {
                        return "已经上传四张图片，重新发布请发送关键字「取消」,取消成功再上传新图片";
                    }
                    break;
                case 'voice':
                    return "暂不支持";
                    break;
                default:
                    return "暂不支持";
                    break;
            }
        });

        $server->serve()->send();
    }
}


