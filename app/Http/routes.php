<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//微信
Route::any('/wechat', 'Wechat\WechatController@serve');
//校历
Route::get('/calendar', function(){return view('calendar.calendar');});
//账号绑定
Route::get('bind/create/{openId}', 'UserController@getBind');
Route::post('bind/create', 'UserController@postBind');
Route::get('bind/modify/{openId}', 'UserController@getModify');
Route::post('bind/modify', 'UserController@postModify'); 
//操作信息返回
Route::get('message/bind/success', function(){return view('msg.bindSuccess');});
Route::get('message/bind/warn', function(){return view('msg.bindWarn');});
Route::get('message/edit/success', function(){return view('msg.editSuccess');});
Route::get('message/edit/warn', function(){return view('msg.editWarn');});

Route::group(['middleware' =>'user'], function(){

    //物品添加
    Route::get('lost/goods/add/{openId?}', 'Lost\GoodsController@getAdd');
    Route::post('lost/goods/add', 'Lost\GoodsController@postAdd');
    
    //标签添加
    Route::get('lost/tag/add/', 'Lost\TagController@getAdd');
    Route::post('lost/tag/add', 'Lost\TagController@postAdd');
    Route::get('lost/tag/edit/{id}', 'Lost\TagController@getEdit');
    Route::post('lost/tag/edit', 'Lost\TagController@postEdit');
    
    //修改密码
    Route::get('password/modify', 'AuthController@getModify');
    Route::post('password/modify', 'AuthController@postModify');
    
    //个人中心
    Route::get('profile','AuthController@getProfile');
    //个人信息
    Route::get('profile/user', 'AuthController@getUserProfile');
    Route::post('profile/user', 'AuthController@postUserProfile');
    //学号认证
    Route::get('profile/auth', 'AuthController@getAuth');
    Route::post('profile/auth', 'AuthController@postAuth');
    //意见反馈
    Route::get('feedback', 'AuthController@getFeedback');
    Route::post('feedback', 'AuthController@postFeedback');
    //发布物品管理
    Route::get('profile/lost/goods', 'AuthController@getGoods');
    Route::get('profile/lost/goods/{id}', 'AuthController@getGoods');
});
    //登录
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', 'AuthController@getLogout');
    //注册
    Route::get('register','AuthController@getRegister');
    Route::post('register','AuthController@postRegister');
    //招领首页
    Route::get('lost', 'Lost\GoodsController@recent');
//    Route::get('category', 'FoundController@category');
    //失物首页
    //Route::get('lost/index', 'LostController@index');
    //标签页
    Route::get('lost/tag/index', 'Lost\TagController@index');
    //检索物品
    Route::get('lost/tag/search', 'Lost\GoodsController@getGoodsByTags');
//    Route::get('goods/edit/{openId}', 'GoodsController@getEdit');
//    Route::post('goods/edit', 'GoodsController@postEdit');
    //物品详情页
    Route::get('lost/goods/info/{id}', 'Lost\GoodsController@getGoodsInfo');

    //忘记密码
    Route::get('password/forget', 'AuthController@getForget');
    Route::post('password/forget', 'AuthController@postForget');

    //重置密码
    Route::get('password/rest/{token}', 'AuthController@getRest');
    Route::post('password/rest', 'AuthController@postRest');

