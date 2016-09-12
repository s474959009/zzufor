<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>八点一刻成绩查询</title>
    {!! Html::style('http://7xpuml.com1.z0.glb.clouddn.com/weui.min.css') !!}
    {!! Html::style('http://7xpuml.com1.z0.glb.clouddn.com/example.css') !!}
    <style>
    .foot
    {
        position:relative;
    }
    .foot .page_foot    
    {
        font-size:14px;
        color:#888;
        margin:2rem auto;
        text-align:center;
    }
    </style>
</head>
<body ontouchstart>
    <div class="container js_container">
        <div class="page">
            <div class="hd">
    			<h1 class="page_title">八点一刻</h1>
    			<p class="page_desc">@yield('desc')</p>
            </div>
            <div class="bd">
				@yield('content')
            </div>
            <div class="foot">
                <p class="page_foot">技术支持 @ AlphaThink</p>
            </div>
        </div>
    </div>
    {!! Html::script('http://7xpuml.com1.z0.glb.clouddn.com/zepto.min.js') !!}    
    {!! Html::script('http://7xpuml.com1.z0.glb.clouddn.com/wechat.js') !!}
    <script type="text/javascript">
    Zepto(function($){
        wechat('hideToolbar');            // 隐藏底部菜单
        wechat('hideOptionMenu');         // 隐藏右上角分享按钮
    })
	@yield('js','')
    </script>
</body>
</html>
