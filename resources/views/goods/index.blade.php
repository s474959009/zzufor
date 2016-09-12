@extends('layout')

@section('title', '发布物品')
@section('style')
    {!! Html::style('font/css/font-awesome.min.css') !!}
    <link rel="stylesheet" href="/css/selectivity-full.min.css">
@stop
@section('content')
    {{ \Carbon\Carbon::setLocale('zh') }}
    <div class="hd tags-hot">
        <div class="weui_panel weui_panel_access">
            <div class="weui_panel_hd weui_media_title">最近标签</div>
            <div class="weui_panel_bd">
                <div class="weui_media_box weui_media_text">
                    <ul class="tags-list">
                        @forelse($tags as $tag)
                            <li class="tag-content">
                                <span value="{{$tag[0]}}" class="tag selection tag-block">{{$tag[1]}}</span>
                            </li>
                        @empty
                            <p>最近没有发布过物品</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper">
        <div class="brand-list">
            <div class="brand-bd cle" id="brand-waterfall">
                @foreach($goods as $key => $val)
                    <div class=" item card  demo-card-header-pic">
                        <a href="/lost/goods/info/{{$val->
						id}}">
                            <div valign="bottom" class="card-header color-white no-border no-padding">
                                @if($val->photos[0])
                                <img class='card-cover' src="{{$val->photos[0]}}"></div>
                                @else
                                <img class='card-cover' src="{{config('lost.pic')}}"></div>
                                @endif
                        </a>
                        <div class="card-content">
                            <div class="card-content-inner">
                                <p>{{$val->goodsName}}</p>
                                <p class="color-gray">发布于{{$val->updated_at->diffForHumans()}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
@section('js')
<script type='text/javascript'>
    Zepto(function($){

        $(document.getElementById("brand-waterfall")).waterfall();
        
        $('.weui_tabbar_item').eq(0).addClass('weui_bar_item_on');
    });

    $.fn.waterfall = function(options) {
        var q = $(".weui_panel_access").width();
        var a = $(".demo-card-header-pic").eq(0).width()*2;
        var margin= q - a;
        var df = {
            item: '.item',
            margin: 0,
            addfooter: true
        };
        options = $.extend(df, options);
        return this.each(function() {
            var $box = $(this), pos = [],
                    _box_width = $box.width(),
                    $items = $box.find(options.item),
                    _owidth = $items.eq(0).width() + options.margin,
                    _oheight = $items.eq(0).height() + options.margin,
                    _num = Math.floor(_box_width/_owidth);
            (function() {
                var i = 0;
                for (; i < _num; i++) {
                    pos.push([i*_owidth,0]);
                }
            })();

            $items.each(function() {
                var _this = $(this),
                        _temp = 0,
                        _height = _this.height() + options.margin;

                for (var j = 0; j < _num; j++) {
                    if(pos[j][1] < pos[_temp][1]){
                        //暂存top值最小那列的index
                        _temp = j;
                    }
                }
                if(pos[_temp][0]!=0){
                    pos[_temp][0] = pos[_temp][0] + margin-10;
                }

                this.style.cssText = 'left:'+pos[_temp][0]+'px; top:'+pos[_temp][1]+'px;';
                //插入后，更新下该列的top值
                pos[_temp][1] = pos[_temp][1] + _height + 20;
                pos[_temp][0] = pos[_temp][0] - margin + 10;
            });

            // 计算top值最大的赋给外围div
            (function() {
                var i = 0, tops = [];
                for (; i < _num; i++) {
                    tops.push(pos[i][1]);
                }
                tops.sort(function(a,b) {
                    return a-b;
                });
                $box.height(tops[_num-1]);

                //增加尾部填充div
                if(options.addfooter){
                    addfooter(tops[_num-1]);
                }

            })();

            function addfooter(max) {
                var addfooter = document.createElement('div');
                addfooter.className = 'item additem';
                for (var i = 0; i < _num; i++) {
                    if(max != pos[i][1]){
                        var clone = addfooter.cloneNode(),
                                _height = max - pos[i][1] - options.margin;
                        clone.style.cssText = 'left:'+pos[i][0]+'px; top:'+pos[i][1]+'px; height:'+_height+'px;';
                        $box[0].appendChild(clone);
                    }
                }
            }

        });
    }
</script>
@stop
