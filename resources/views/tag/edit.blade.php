@extends('layout')

@section('title', '修改标签')

@section('content')
    <div class="hd">
        <h2 class="page_title">修改标签</h2>
        <p class="page_desc">先选择所添加的标签类型，再输入标签名称。</p>
    </div>
    <div class="weui_cells weui_cells_form">
        <form class="">
            <div class="weui_cell weui_cell_select">
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select" name="select1">
                        <option selected="" value="0">请选择标签</option>
                        <option value="1">地点标签</option>
                        <option value="2">物品标签</option>
                        <option value="3">特征标签</option>
                    </select>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">标签名称</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" placeholder="请输入标签名称"/>
                </div>
            </div>
        </form>
    </div>
    <div class="weui_btn_area">
        <a id="showTooltips" href="javascript:" class="weui_btn weui_btn_primary">确定</a>
    </div>
    {{--<script src="/lib/zepto.min.js"></script>--}}
    <script>

        //提交表单
        var submit = function (){
            $.ajax({
                type:'POST',
                url:'/lost/tag/edit',
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
