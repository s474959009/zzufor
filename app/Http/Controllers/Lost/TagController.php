<?php

namespace App\Http\Controllers\Lost;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTagRequest;
use App\Utils\Predis;
use App\Model\Tag;

class TagController extends Controller
{

    //标签首页
    public function index()
    {
        //rootId为1地点标签
        $tags1 = Tag::getTags(1);
        //rootId为2物品标签
        $tags2 = Tag::getTags(2);
        //rootId为3特征标签
        $tags3 = Tag::getTags(3);

        $tags = new Tag();       
        //获取热门标签 
        $redis = new Predis();
        $tagsId = $redis->getTwoDaysTop(9);
        $tags = $tags->getTagsById($tagsId);       
       // $tags = [];           
        return view('tag.index')
            ->with(['tags'=>$tags, 'tags1'=>$tags1, 'tags2'=>$tags2, 'tags3'=>$tags3]);
    }

    //添加标签
    public function getAdd()
    {
        //获取标签分类
        $tags = Tag::getRootTag();
        //dd($tags);
        return view('tag.add')
            ->with('root',$tags);
    }


    /**
     * 保存标签
     * @param CreateTagRequest $request
     * @return array|string
     */
    public function postAdd(CreateTagRequest  $request)
    {
        $data = $request->all();

        $tag = new Tag;
        $tag->rootId = $data['rootId'];
        $tag->tagName = $data['tagName'];
        $tag->userId = 1;

        if($tag->save())
        {
            return 'success';
        } else {
            return array('err' => ['添加标签失败']);
        }

    }

}
