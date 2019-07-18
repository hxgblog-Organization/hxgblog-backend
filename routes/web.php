<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * 用户认证失败的路由
 */
Route::any('unAuth', function () {
    return responseToJson(3,'未认证或认证失败');
})->name('unAuth');


/**
 * 前台入口路由
 */
Route::get('/', function () {
    return view('welcome');
});
Route::namespace('FrontControllers')->group(function () {

    Route::get('mainPage', 'MainpageController@showMainPage');                   //显示主页面
    Route::post('registerUser', 'UserController@registerUser');                  //注册新用户
    Route::get('showArticalPage', 'ArticalController@showArticalPage');          //显示文章页面
    Route::get('byTypeSelectArtical', 'ArticalController@byTypeSelectArtical');  //根据文章类型搜索文章
    Route::get('showArticalDetail', 'ArticalController@showArticalDetail');      //查一篇文章的所有内容
    Route::post('byNameSelectArtical', 'ArticalController@byNameSelectArtical'); //根据文章名字模糊查询文章
    Route::get('byTopIdSelectAllComment','ArticalController@byTopIdSelectAllComment');//查某一个评论的所有评论
    Route::get('getArticalAllType', 'ArticalController@getArticalAllType');         //获取文章类型

    Route::get('selectAllAlbumInformation', 'AlbumController@selectAllAlbumInformation'); //查询所有相册信息
    Route::post('judgeQuestionAnswer', 'AlbumController@judgeQuestionAnswer');   //判断相册问题答案是否正确
    Route::get('byAlbumIdSelectPhoto', 'AlbumController@byAlbumIdSelectPhoto');  //根据相册的ID查询照片

    Route::get('selectLeaveMessage', 'leaveMessageController@selectLeaveMessage');  //查询留言信息
    //前台需要验证的路由
    Route::middleware('loginCheck', 'auth:api', 'updateToken:api')->group(function () {
        Route::post('sendReplayComment','ArticalController@sendReplayComment');  //添加回复评论
        Route::post('addPublishComment', 'ArticalController@addPublishComment'); //添加评论
        Route::post('deleteArticalComment', 'ArticalController@deleteArticalComment');//删除文章评论
        Route::post('praiseOrTrampleArtical', 'ArticalController@praiseOrTrampleArtical'); //文章赞/踩

        Route::post('replayMessage', 'LeaveMessageController@replayMessage');           //回复留言
        Route::post('deleteLeaveMessage', 'leaveMessageController@deleteLeaveMessage'); //删除留言
        Route::post('addLeaveMessage', 'leaveMessageController@addLeaveMessage');       //添加留言

    });
});
//获取资源需要验证
Route::namespace('CommonControllers')->group(function () {
    /**
     * 登录、退出路由
     */
    // '前台/后台' 用户退出
    Route::middleware('auth:api')->group(function () {
        Route::get('frontLogout', 'LoginController@frontLogout');
        Route::get('backLogout', 'LoginController@backLogout');
    });
    //前台用户登录
    Route::post('frontLogin', 'LoginController@frontLogin');

    //后台用户登录
    Route::post('backLogin', 'LoginController@backLogin');

    //验证前台用户是否登录
    Route::get('checkLogin', 'LoginController@checkLogin');

    //判断后台管理员与前台用户是否同时在线
    Route::get('checkUserOrAdminLogin', 'LoginController@checkUserOrAdminLogin');

    Route::get('getPhoto', 'ObtainFileController@getPhoto');
    Route::middleware('loginCheck', 'auth:api', 'updateToken:api')->group(function () {
        Route::get('getUserInformation', 'UserController@getUserInformation');        //获取用户信息
        Route::post('updateUserInformation', 'UserController@updateUserInformation'); //修改用户信息
    });
});
Route::namespace('BackControllers')->group(function (){
    Route::middleware('loginCheck', 'auth:api', 'updateToken:api')->group(function () {
        Route::get('getArtical', 'MaArticalController@getArtical');                        //获取文章
        Route::get('combinateSelectArtical', 'MaArticalController@combinateSelectArtical');//组合查询文章
        Route::get('byTypeSelectArtical', 'MaArticalController@byTypeSelectArtical');      //根据文章类型搜索
        Route::get('getAloneArtical', 'MaArticalController@getAloneArtical');              //查询单个文章信息
        Route::post('addArtical', 'MaArticalController@addArtical');                       //添加文章
        Route::post('deleteArtical', 'MaArticalController@deleteArtical');                 //删除文章
        Route::post('updateArtical', 'MaArticalController@updateArtical');                 //修改文章信息

        Route::get('getAlbumInfor', 'MaAlbumController@getAlbumInfor');                         //获取相册信息
        Route::post('addAlbum', 'MaAlbumController@addAlbum');                                  //添加相册信息
        Route::post('deleteAlbum', 'MaAlbumController@deleteAlbum');                            //删除信息
        Route::post('updateAlbumInfor', 'MaAlbumController@updateAlbumInfor');                  //更新相册信息
        Route::post('addAlbumSecretSecurity', 'MaAlbumController@addAlbumSecretSecurity');      //添加相册密保
        Route::post('deleteAlbumSecretSecurity', 'MaAlbumController@deleteAlbumSecretSecurity');//删除相册密保
        Route::post('updateAlbumSecretSecurity', 'MaAlbumController@updateAlbumSecretSecurity');//修改相册密保



    });

});
/**
 * 后台入口路由
 */
Route::get('/admin', function () {
    return view('admin');
});