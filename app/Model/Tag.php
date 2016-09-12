<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Predis;
use DB;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'tagName',
        'userId',
        'category'
    ];

    //获取根标签
    public static function getRootTag()
    {
        return self::select('id','tagName')->where('category',1)->get();
    }

    //获取子标签(ID、TagName)
    public static function getChildTag()
    {
        return self::select('id','tagName as text')->where('category',0)->get();
    }

    //根据RootId获取子标签
    public static  function getTags($rootId)
    {
        return self::select('id','tagName')->where('category',0)->where('rootId', $rootId)->get()->toArray();
    }

    //根据ID获取热门标签
    public function getTagsById($ids)
    {   
        if(!is_array($ids))  $ids = explode(',',$ids);
        
        $arr = array();
        $redis = new Predis;

        //获取标签占有率，总数，名称，ID。判断是否有缓存
        $tagNames = array_map(function($tagId) use($redis){
                        $rate = $redis->getTagRate($tagId);
                        $count = $redis->getTagGoodsCount($tagId);    
                        $tagName = Predis::getTagHot($tagId);
                        if(!$tagName)
                        {
                            $tagName = $this->find($tagId)->tagName;
                            Predis::setTagHot($tagId, $tagName);       
                        }
                        //标签Id,标签名字,标签下物品数量,标签占有率
                        return array($tagId,  $tagName, $count, $rate);                           
                    },$ids);
        return $tagNames;
    }
    
    public function getTagNameById($ids)
    {

        if(!is_array($ids))  $ids = explode(',',$ids);

        $arr = array();
        $redis = new Predis;
        $tagNames = array_map(function($tagId) use($redis){
                        $tagName = $this->find($tagId)->tagName;
                        return array($tagId, $tagName);
                    },$ids);
        return $tagNames;
    }
    
}
