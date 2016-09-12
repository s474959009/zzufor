<?php

namespace App\Utils;

use Redis;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Tests\DataCollector\DumpDataCollectorTest;

class Predis
{
    /**
     * 发布物品时，创建物品和标签关系
     * @param $goodsId 物品Id
     * @param $tagsId 标签Id
     */
    public function saveCache($goodsId, $tagsId)
    {
        $goods_key = 'lost:goods:'.$goodsId.':tags';

        foreach($tagsId as $tagId)
        {

            $tag_key = 'lost:tag:'.$tagId.':goods';

            Redis::sadd($tag_key, $goodsId);
            Redis::sadd($goods_key, $tagId);
            Redis::incrby('lost:tags:count',1);
            $this->addRecord($tagId);
        }
    }

    /**
     * 添加物品时,增加标签记录
     * @param $tagId
     */
    public function addRecord($tagId)
    {
        $key = 'lost:record:'.date('Ymd');
        Redis::zincrby($key, 1, $tagId);
    }


    /**
     * 获取指定一天的热门标签记录
     * @param $date Ymd
     * @param $start 区间开始
     * @param $stop 区间结束
     * @return mixed 满足条件的标签Id
     */
    public function getOneDayRecord($date, $start, $stop)
    {
        $key = 'lost:record:'.$date;
        return Redis::zrevrange($key, $start, $stop);
    }

    /**
     * 获取多天热门标签记录
     * @param $dates 日期数组
     * @param $outKey 合并集合的KEY
     * @param $start 范围开始
     * @param $stop 范围结束
     * @return mixed
     */
    public function getMultiDaysRecord($dates, $outKey, $start, $stop)
    {
        $keys = array_map(function($date){
            return 'lost:record:'.$date;
        },$dates);

        $weights = array_fill(0, count($keys), 1);

        Redis::zunionstore($outKey, $keys, $weights);
        return Redis::zrevrange($outKey, $start, $stop);
    }


    /**
     * 获取3天热门标签
     * @param $num 标签个数
     * @return mixed
     */
    function getTwoDaysTop($num)
    {
    
        $days = array();
        $dt = Carbon::now();
        $key = 'lost:record:top:'.$dt->format('Ymd');
    
        if(Redis::exists($key)){
           return Redis::zrevrange($key, 0, 9) ;
        }
        
        for ($i = 0; $i <= 1; $i++)
        {           
            $days[] = $dt->subDays($i)->format('Ymd');
        }
        return $this->getMultiDaysRecord($days, $key, 0, $num);
    }
    /**
     * 获取最近一个月的热门标签
     * @param $num 标签个数
     * @return mixed
     */
    public function getCurrentMonthTop($num)
    {
        $dates = $this->getCurrentMonthDates();
        return $this->getMultiDaysRecord($dates, 'lost:record:current_month', 0, $num);
    }

    /**
     * 根据标签Id获取物品Id
     * @param $tagId
     * @return array
     */
    public function getGoodsIds($tagId)
    {
        $key = 'lost:tag:'.$tagId.':goods';

        return  Redis::smembers($key);
    }

    /**
     * 根据物品Id获取标签Id
     * @param $goodsId
     * @return mixed
     */
    public function getTagIds($goodsId)
    {
        $key = 'lost:goods:'.$goodsId.':tags';

        return Redis::smembers($key);
    }

    /**
     * 创建单个物品信息缓存
     * @param $goodsId
     * @param $data
     */
    protected function setGoodsInfo($goodsId, $data)
    {
        $key = 'lost:goods:'.$goodsId.':info';
        Redis::hmset($key, $data);
    }

    /**
     * 删除单个物品的缓存
     * @param $goodsId
     */
    protected function delGoodsInfo($goodsId)
    {
        $key = 'lost:goods:'.$goodsId.':info';
        Redis::del($key);
    }

    /**
     * 发布物品时加入首页缓存
     * @param $goodsId
     * @param $data
     */
    public function setGoodsIndex($goodsId, $data)
    {
        $key = 'lost:index:goods';
        $count = Redis::lpush($key, $goodsId);
        //将物品Id插入索引列表
        if($count > config('goods.index_counts'))
        {
            $goodsId = Redis::Rpop($key);
            $this->delGoodsInfo($goodsId);
            $this->setGoodsInfo($goodsId, $data);
        } else {
            $this->setGoodsInfo($goodsId, $data);
        }
    }


    /**
     * 获取标签占有率
     * @param $tagId
     * @return string
     */
    public function getTagRate($tagId)
    {
        $key = 'lost:tags:count';
        $tagsCount = Redis::get($key);
        $goodsCount = $this->getTagGoodsCount($tagId);
        $rate = round($goodsCount/$tagsCount*100);
//        if($rate<0.3){
//            $rate * 3;
//        }
        return $rate.'%';
    }

    /**
     * 取得某一标签使用次数
     * @param $tagId
     * @return mixed
     */
    public function getTagGoodsCount($tagId)
    {
        $key = 'lost:tag:'.$tagId.':goods';
        return Redis::scard($key);
    }

    /**
     * 获取最近一个月的日期
     * @return array
     */
    public function getCurrentMonthDates()
    {
        $dt = Carbon::now();
        $days = $dt->daysInMonth;

        $dates = array();
        for ($day = 1; $day <= $days; $day++)
        {
            $dt->day=$day;
            $dates[] = $dt->format('Ymd');
        }
        return $dates;
    }

    /**
     * 根据标签ID获取相关物品ID
     * @param $tagsId
     * @return array
     */
    public static function getGoodsByTags($tagsId)
    {
        $keys = [];

        foreach($tagsId as $tagId)
        {
            $keys[] = 'lost:tag:'.$tagId.':goods';
        }

        return Redis::sinter($keys);
    }

        //设置用户发布图片临时key
    public static function setPhotoKey($openId, $url)
    {
        $key = 'lost:'.$openId.':photos';
        $num =  Redis::sadd($key, $url);
        //设置10分钟失效
        Redis::expire($key, 600);
        return $num;
    }

        //获取用户发布的图片
    public static function getPhotoKey($openId)
    {
        $key = 'lost:'.$openId.':photos';
        $photos = Redis::smembers($key);
        if($photos == null)
        {
            return null;
        } else {
            return $photos;
        }
    }

    
    //删除用户临时key
    public static function delPhotoKey($openId)
    {
        $key = 'lost:'.$openId.':photos';
       
        return Redis::del($key);
       
        
    }    

    //返回当前图片集合数量
    public static function getPhotoNum($openId)
    {
        $key = 'lost:'.$openId.':photos';
        return Redis::scard($key);
    }
    
    //设置标签ID和名称对应集合
    public static function setTagHot($tagId, $tagName)
    {

        $dt = Carbon::now();
        $key = 'lost:tag:top:'.$dt->format('Ymd');
        Redis::hset($key, $tagId, $tagName);
        return ;
    }

    //根据标签ID从集合中取
    public static function getTagHot($tagId)
    {
        $dt = Carbon::now();
        $key = 'lost:tag:top:'.$dt->format('Ymd');
        return Redis::hget($key, $tagId);
    }
}
