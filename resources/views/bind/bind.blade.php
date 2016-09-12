    @extends('bind.layout')         

    @section('desc', '微信成绩查询服务')

    @section('content')
   <!-- <div class="weui_cells_title">每位同学只能绑定自己的学号</div> -->
   
     <form id='form'>
        <div class="weui_cells weui_cells_form">
        <div class="weui_cell weui_studentId">
            <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text"  name="studentId"  placeholder="请输入学号"/>
            </div>
        </div>
        <div class="weui_cell weui_password">
            <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                 <input type="hidden" name="openId" value="{{ $openId }}" />
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                 <input class="weui_input" type="text" name="password"  placeholder="请输入查成绩密码"/>
            </div>
        </div>
       </form>
    </div>
    <div class="weui_cells_tips">每位同学只能绑定自己的学号</div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_primary" href="javascript:post()">确定</a>
    </div>
    @stop

    @section('js')
       var post = function(){
        $.ajax({
            type:'POST',
            url:'/bind/create',
            data:$('form').serialize(),
            success:function(data){
                    if(data == 'success'){ 
                         window.location.href = 'http://bdyk.zzufor.com/message/bind/success';
                    } else if (data == 'warn' || data == ""){ 
                         window.location.href = 'http://bdyk.zzufor.com/message/bind/warn';
                    } else                                  
                         tooltips(data);
                    },
            error:function(xhr){
                    tooltips($.parseJSON(xhr.responseText));
                    }
        })
        
        }
        
       //错误提示栏        
       var tooltips = function(data){
           for(var key in data){
              if(key.length != 0){
                var tag = ".weui_toptips";
                $('form').prepend('<div class="weui_toptips weui_warn js_tooltips">'+data[key][0] +'</div>');
                return showtips(tag);
              }
           } 
       }
       
       //显示错误信息
       var showtips = function(data){
             $(data).first().show();
              setTimeout(function (){
                  $(data).first().remove();;
              }, 3000);        
       }   
    @stop
    
