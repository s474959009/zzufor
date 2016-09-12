@extends('layout')

@section('title', '失物招领-学号认证')

@section('content')
    <div class="hd">
        <h2 class="page_title">意见反馈</h2>
    </div>
    <div class="weui_cells weui_cells_form">
        <form class="">
            <div class="weui_cells">
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">标题</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="title" type="text" placeholder="请输入标题"/>
                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" name="content" placeholder="在此输入对我们想说的话" rows="3"></textarea>
                        <div class="weui_textarea_counter"><span>0</span>/200</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="weui_btn_area">
        <a id="showTooltips" href="javascript:" class="weui_btn weui_btn_primary" onclick="submit();">确定</a>
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
                url:'/feedback',
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