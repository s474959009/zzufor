<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>八点一刻-失物招领</title>
    <link rel="stylesheet" href="/css/weui.css">
    <link rel="stylesheet" href="/css/example.css">
    @yield('style','')
</head>
<body>
<div class="weui_tab" style="height: 100%">
    <div class="weui_tab_bd">
        @yield('content')
    </div>
    <div class="weui_tabbar">
        <a href="{{url('lost')}}" class="weui_tabbar_item">
            <div class="weui_tabbar_icon">
                <img src="/images/icon_nav_button.png" alt="">
            </div>
            <p class="weui_tabbar_label">主页</p>
        </a>
        <a href="{{url('lost/tag/index')}}" class="weui_tabbar_item">
            <div class="weui_tabbar_icon">
                <img src="/images/icon_nav_search_bar.png" alt="">
            </div>
            <p class="weui_tabbar_label">查找</p>
        </a>
        <a href="{{url('profile')}}" class="weui_tabbar_item">
            <div class="weui_tabbar_icon">
                <img src="/images/icon_nav_cell.png" alt="">
            </div>
            <p class="weui_tabbar_label">管理</p>
        </a>
    </div>
</div>
<script src="/lib/zepto.min.js"></script>
@yield('js','')
</body>
</html>
