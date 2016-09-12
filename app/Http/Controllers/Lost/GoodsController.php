<?php

namespace App\Http\Controllers\Lost;


use EasyWeChat;
use App\Events\GoodsCreate;
use Illuminate\Http\Request;
use Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGoodsRequest;
use Illuminate\Database\QueryException;
use App\Model\Goods;
use App\Model\Tag;
use App\Utils\Predis;
use Illuminate\Support\Facades\Input;

class GoodsController extends Controller
{
    //物品首页
    public function index()
    {

    }

    public function recent()
    {
        $tags = new Tag();
        $goods = Goods::all();

        $redis = new Predis();
        $tagsId = $redis->getTwoDaysTop(9);
        $tags = $tags->getTagsById($tagsId); 
            
        return view('goods.index')
            ->with(['goods'=>$goods, 'tags' => $tags]);
    }

    public function getAdd($openId)
    {

        if($openId)
        {
            $photos = Predis::getPhotoKey($openId);
        }

        //获取所有子标签
        $tags = Tag::getChildTag();
        $tags = json_encode($tags, JSON_UNESCAPED_UNICODE);
        
        return view('goods.add')
            ->with(['tags'=>$tags, 'photos'=>$photos, 'openId'=>$openId]);
    }

    public function postAdd(CreateGoodsRequest $request)
    {
        $photos = $request->photos;
        $data = $request->all();
        $data['photos'] = json_encode(explode(',',$photos), JSON_UNESCAPED_SLASHES);


        try {
            $goods = Goods::create($data);
            $openId = $data['openId'];
             Event::fire(new GoodsCreate($goods));
            Predis::delPhotoKey($openId);
        }catch (QueryException $e) {
            return array('err' => ['物品发布失败']);
        }

        return 'success';
    }

    public function getGoodsByTags()
    {
        $tagsId = Input::get('tag');

        $tagsId = explode('-',$tagsId);

        $goodsId = Predis::getGoodsByTags($tagsId);

        $goods = Goods::find($goodsId);
        $tags = Tag::find($tagsId)->pluck('tagName')->toArray();

        return view('goods.list')
            ->with(['goods'=> $goods, 'tags'=>$tags]);
    }
        
    public function getGoodsInfo($id)
    {
        $info = Goods::find($id);
        if($info)
        {
            $tagIds = $info->tags;
            $tags = new Tag();
            $tags = $tags->getTagNameById($tagIds); 

            return view('goods.info')
                    ->with(['info'=>$info, 'tags' => $tags]);
        }else {
            abort(404);
        }
        
        

    }
}
