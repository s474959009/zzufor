@extends('layout')

@section('title', '失物招领-分类页面')

@section('content')
    <div class="hd">
        <h2 class="page_title">分类详情</h2>
        <p class="page_desc">选择一个或多个标签，查看相应物品</p>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd weui_media_title">热门标签</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_text">
                <ul class="popular-tags">
                    @forelse($tags as $val)
                        <li class="tag1"> <em>{{$val[2]}}</em>
                            <span> <strong>{{$val[1]}}</strong> </span>
                            <span class="perc" style="width: {{$val[3]}}"></span>
                        </li>
                    @empty
                        <p>最近还没有发布过物品</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd weui_media_title">地点标签</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_text">
                <ul class="tags-list">
                    @foreach($tags1 as $tags)
                        <li class="tag-content">
                            <span value="{{$tags['id']}}" class="tag selection tag-block">{{$tags['tagName']}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd weui_media_title">物品标签</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_text">
                <ul class="tags-list">
                    @foreach($tags2 as $tags)
                    <li class="tag-content">
                        <span value="{{$tags['id']}}" class="tag selection tag-block">{{$tags['tagName']}}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd weui_media_title">特征标签</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_text">
                <ul class="tags-list">
                    @foreach($tags3 as $tags)
                        <li class="tag-content">
                            <span value="{{$tags['id']}}" class="tag selection tag-block">{{$tags['tagName']}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="weui_btn_area">
        <a id="showTooltips" href="javascript:" class="weui_btn weui_btn_primary" onclick="submit();">查找物品</a>
    </div>
@stop
@section('js')
    <script>
        
        //菜单按钮状态
        $('.weui_tabbar_item').eq(1).addClass('weui_bar_item_on');

        //选择标签
        $('.selection').click(function(){

            selection = $(this);

            if(selection.hasClass('active')){
                selection.removeClass('active')
            }else {
                selection.addClass('active');
            }
        });

        //
        var submit = function(){
            var data = '';
            $('.active').each(function(index){

                if(index!=0){
                    data += "-";
                }

                data+=$(this).attr('value');
            });

            window.location.href="{{url('lost/tag/search')}}"+"?tag="+data;
        }


    </script>
@stop
