@extends('layout')

@section('title', '失物招领-修改密码')

@section('content')
    <div class="hd">
        <h2 class="page_title">修改密码</h2>
        <p class="page_desc">功能介绍......</p>
    </div>
    <div class="weui_cells weui_cells_form">
        <form class="">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">新密码</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="password" type="password" placeholder="请输入新密码"/>
                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">确认密码</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="repassword" type="password" placeholder="请再次输入密码"/>
                </div>
            </div>
        </form>
    </div>
    <div class="weui_btn_area">
        <a id="showTooltips" href="javascript:" class="weui_btn weui_btn_primary" onclick="submit();">修改密码</a>
    </div>
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">操作成功</p>
        </div>
    </div>
@stop
@section('js')
    <script>
        //提交表单
        var submit = function (){
            $.ajax({
                type:'POST',
                url:'/password/modify',
                data: $('form').serialize(),
                success:function(data){
                    if(data == 'success'){
                        toast()
                    } else
                        tooltips(data);
                },
                error:function(xhr){
                    tooltips($.parseJSON(xhr.responseText));
                }
            });
        }

        //错误提示栏
        var tooltips = function(data){
            for(var key in data){
                if(key.length != 0){
                    var tag = ".weui_toptips";
                    $('.hd').prepend('<div class="weui_toptips weui_warn js_tooltips">'+data[key][0] +'</div>');
                    return showtips(tag);
                }
            }
        }

        //操作成功
        var toast = function() {
            var toast = $('#toast');
            if (toast.css('display') != 'none') {
                return;
            }

            toast.show();
            setTimeout(function () {
                location.reload();
            }, 1200);
        }


        //显示错误信息
        var showtips = function(data){
            $(data).first().show();
            setTimeout(function (){
                $(data).first().remove();;
            }, 3000);
        }
    </script>
@stop