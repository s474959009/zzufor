@extends('layout')

@section('title', '失物招领-物品详情')
@section('style')
<link rel="stylesheet" href="http://7xpuml.com1.z0.glb.clouddn.com/swiper-3.3.1.min.css">
@stop
@section('content')
<div class="weui_panel weui_panel_access">
        <div class="swiper-container">
                <div class="swiper-wrapper">
                @if($info->photos[0])
                    @foreach($info->photos as $photo)
                    <div class="swiper-slide">
                        <img class="img-responsive" src="{{$photo.'?imageView2/1/w/320/h/320/q/100'}}">
                    </div>
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <img class="img-responsive" src="http://7xpq7s.com1.z0.glb.clouddn.com/Fq13A_RGknAMh5nR3UNj1kr8Lmvp">
                    </div>
                @endif
                </div>
        </div>
</div>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd">物品信息</div>
    <div class="weui_panel_bd">
        <div class="weui_media_box weui_media_text">
            <h4 class="weui_media_title">{{$info->goodsName}}</h4>
            <p class="weui_media_desc">{{$info->desc}}</p>
            <ul class="weui_media_info">
                <li class="weui_media_info_meta">标签：</li>
                @foreach($tags as $val)
                <li class="weui_media_info_meta"><a href="{{action("Lost\GoodsController@getGoodsByTags",array('tag'=>$val[0]))}}">{{$val[1]}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
    <div class="weui_btn_area">
        <a href="javascript:;" class="weui_btn weui_btn_primary" id="showActionSheet">我是失主</a>
    </div>
    <div id="actionSheet_wrap">
        <div class="weui_mask_transition" id="mask"></div>
        <div class="weui_actionsheet" id="weui_actionsheet">
            <div class="weui_actionsheet_menu">
                <div class="weui_actionsheet_cell">联系人：张同学</div>
                <div class="weui_actionsheet_cell"><a href="tel:18838988879">立刻电话联系</a></div>
                <div class="weui_actionsheet_cell"><a href="sms:18838988879">立刻短信联系</a></div>
            </div>
            <div class="weui_actionsheet_action">
                <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
            </div>
        </div>
    </div>
@stop
@section('js')
<script src="http://7xpuml.com1.z0.glb.clouddn.com/swiper-3.3.1.jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var mySwiper = new Swiper ('.swiper-container', {
        loop: true,
        }); 
});
    $('#showActionSheet').on('click',function(){
        var mask = $('#mask');
        var weuiActionsheet = $('#weui_actionsheet');
       
         weuiActionsheet.addClass('weui_actionsheet_toggle');
        mask.show().addClass('weui_fade_toggle').one('click', function () {
            hideActionSheet(weuiActionsheet, mask);
        });
        
        $('#actionsheet_cancel').one('click', function () {
            hideActionSheet(weuiActionsheet, mask);
        });
        
        weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');

        function hideActionSheet(weuiActionsheet, mask) {
            weuiActionsheet.removeClass('weui_actionsheet_toggle');
            mask.removeClass('weui_fade_toggle');
            weuiActionsheet.on('transitionend', function () {
                mask.hide();
            }).on('webkitTransitionEnd', function () {
                mask.hide();
            }) 
        }
    })
</script>
@stop
