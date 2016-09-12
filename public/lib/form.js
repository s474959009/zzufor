//上传物品提交表单数据
var postData = function() {
    var data = $('form').serializeArray();
    data.push({name:"tags", value:getTags()});
    data.push({name:"photos", value:getPhotos()});
    return data;
}

//获取下拉列表数据
var getTags = function() {
    var droplist = [];
    $(".selectivity-multiple-input-container .selectivity-multiple-selected-item").each(function(index){
        droplist[index] = $(this).attr('data-item-id');
    });
    return droplist;
}

//获取图片地址
var getPhotos = function(){
    var photos = [];
    $(".weui_uploader_files img").each(function(index) {
        photos[index] = $(this).attr('src');
    });
    return photos;
}

//构造input
var setInput = function(name, val) {
    var html = "<span style='display:none' class='photo-url' name="+name+" value = "+val+" ></span>";
    $('form').append(html);
}
