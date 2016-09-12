@extends('layout')

@section('title', '失物招领-个人中心')

@section('content')
    <div class="hd">
        <h2 class="page_title">个人中心</h2>
        <p class="page_desc">个人信息将作为发布物品时的默认联系方式</p>
    </div>
    <div class="weui_panel">
        <div class="weui_panel_hd">账户管理</div>
        <div class="weui_panel_bd">
            <div class="weui_media_box weui_media_small_appmsg">
                <div class="weui_cells weui_cells_access">
                    <a class="weui_cell" href="{{ url('profile/user') }}">
                        <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>个人信息</p>
                        </div>
                        <span class="weui_cell_ft"></span>
                    </a>
                    <a class="weui_cell" href="{{ url('profile/auth') }}">
                        <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>学号认证</p>
                        </div>
                        <span class="weui_cell_ft"></span>
                    </a>
                    <a class="weui_cell" href="{{ url('feedback') }}">
                        <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <p>意见反馈</p>
                        </div>
                        <span class="weui_cell_ft"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd">已发布物品</div>
        <div class="weui_panel_bd">
            @foreach($goods as $good)
            <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                <div class="weui_media_hd">
                     @if($good['photos'][0])
                     <img class="weui_media_appmsg_thumb" src="{{$good['photos'][0]}}" />
                     @else
                     <img class="weui_media_appmsg_thumb" src="{{config('lost.pic')}}" />
                     @endif
                </div>
                <div class="weui_media_bd">
                    <h4 class="weui_media_title">{{$good['goodsName']}}</h4>
                    <p class="weui_media_desc">{{$good['desc']}}</p>
                </div>
            </a>
            @endforeach
        @if($goods)
        </div>
        <a class="weui_panel_ft" href="{{ url('profile/lost/goods') }}">查看更多</a>
        @else
        <p class="weui_media_box weui_media_appmsg">你还没有发布过物品</p>
        </div>
        @endif
    </div>
    <div class="weui_btn_area">
        <a  href="{{url('logout')}}" class="weui_btn weui_btn_primary" >退出</a>
    </div>
@stop
@section('js')
<script>
    //菜单按钮状态
    $('.weui_tabbar_item').eq(2).addClass('weui_bar_item_on');
</script>
@stop
