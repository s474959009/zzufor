<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>八点一刻成绩查询</title>
    {!! Html::style('http://7xpuml.com1.z0.glb.clouddn.com/weui.min.css') !!}
    {!! Html::style('http://7xpuml.com1.z0.glb.clouddn.com/example.css') !!}
</head>
<body ontouchstart>
    <div class="container">
        <div class="page">
            <div class="weui_msg">
                @yield('icon')
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">@yield('status')</h2>
                    @yield('content')
                </div>
                <div class="weui_opr_area">
                    <p class="weui_btn_area">
                        <a href="javascript:wechat('closeWebView');" class="weui_btn weui_btn_primary">确定</a>                        
                    </p>
                </div>
                <div class="weui_extra_area">
                    <a href="#">技术支持@AlphaThink</a>
                </div>
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
    </script>
</body>
</html>
