@extends('layout')

@section('title', '失物招领-发布管理')

@section('content')
    <div class="hd">
        <h2 class="page_title">发布管理</h2>
        <p class="page_desc">管理已发布的物品信息，可以修改或更改物品状态</p>
    </div>
    <div class="weui_panel weui_panel_access">
        <div class="weui_panel_hd">已发布物品</div>
        <div class="weui_panel_bd">
            @foreach($goods as $val)
            <a href="{{$val->id}}" class="weui_media_box weui_media_appmsg">
                <div class="weui_media_hd">
                @if($val->photos[0])
                <img class="weui_media_appmsg_thumb" src="{{$val->photos[0]}}" />
                @else
                <img class="weui_media_appmsg_thumb" src="{{config('lost.pic')}}" /> 
                @endif
                </div>
                <div class="weui_media_bd">
                    <h4 class="weui_media_title">{{$val->goodsName}}</h4>
                    <p class="weui_media_desc">{{$val->desc}}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
            {!! $goods->render() !!}
@stop
