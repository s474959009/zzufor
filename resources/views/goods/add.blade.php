@extends('layout')

@section('title', '发布物品')
@section('style')
    {!! Html::style('font/css/font-awesome.min.css') !!}
    <link rel="stylesheet" href="/css/selectivity-full.min.css">
@stop
@section('content')
    <div class="hd">
        <h2 class="page_title">发布物品</h2>

        <p class="page_desc">物品标签可以输入关键字检索，一个物品可以有多个标签，最多上传4张图片，也可以不上传图片。</p>
    </div>
    <form class="">
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">物品名称</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="goodsName" type="text" placeholder="请输入物品名称"/>
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <input type="hidden" name="openId" value="{{$openId}}"/>
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label">拾到时间</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="picked_at" type="date" value="" placeholder="选择拾到物品的时间"/>
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd" ><label class="weui_label">选择标签</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <span id="single-input-with-labels" class="selectivity-input"></span>
                </div>
            </div>
        </div>

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" name="desc" placeholder="请输入物品相关信息" rows="3"></textarea>
                    <div class="weui_textarea_counter"><span>0</span>/200</div>
                </div>
            </div>
        </div>

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <div class="weui_uploader">
                        <div class="weui_uploader_hd weui_cell">
                            <div class="weui_cell_bd weui_cell_primary">图片上传</div>
                            <div class="weui_cell_ft">0/3</div>
                        </div>
                        <div class="weui_uploader_bd">
                            <ul class="weui_uploader_files">
                                @if($photos)
                                @foreach($photos as $photo)
                                <img  src="{{config('qiniu.bucket_url').$photo}}" class="weui_uploader_file"/>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="weui_btn_area">
        <a id="showTooltips" href="javascript:" class="weui_btn weui_btn_primary" onclick="submit();">确定</a>
    </div>
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>

            <p class="weui_toast_content">添加成功</p>
        </div>
    </div>
@stop
@section('js')
    <script src="/js/selectivity-full.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <script src="/lib/form.js"></script>
    <script>

     $('#single-input-with-labels').selectivity({   
            multiple: true,
            items: {!! $tags !!},
            placeholder: '点击选择标签',
            searchInputPlaceholder: '11'
        });

        //提交表单
        var submit = function (){
            var data = postData();
            
            $.ajax({
                type:'POST',
                url:'/lost/goods/add',
                data: data,
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
      //      setTimeout(function () {
      //          window.history.back();
      //      }, 1200);
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
