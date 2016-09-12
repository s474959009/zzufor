<?php

namespace App\Listeners;

use App\Events\GoodsCreate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Utils\Predis;

class CreateGoodsToRedis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GoodsCreate  $event
     * @return void
     */
    public function handle(GoodsCreate $event)
    {
        $goods = $event->goods;
        
        $redis = new Predis();

        $goodsId = $goods->id;
        $tagsId = explode(',',$goods->tags);

        //缓存物品标签关系
        $redis->saveCache($goodsId, $tagsId);
        //加入首页缓存列表
    //    $redis->setGoodsIndex($goodsId, $goods->toArray());
    }
}
