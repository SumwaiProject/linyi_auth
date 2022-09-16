<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group("user", function (){
    // 注册
    Route::rule("signup", "user/signup");
    // 登录
    Route::rule("login", "user/signin");
    Route::rule("signin", "user/signin");

    // 查询用户是否在线
    Route::rule("online/[:token]", "user/online");

    // 查询用户服务
    Route::rule("service/[:service_name]", "user/service");

    // 使用卡密
    Route::rule("kami/[:token]/[:kami]", "user/cdk");

    // 找回密码
    Route::rule("forget/password", "user/forget_password");
    // 重置密码
    Route::rule("reset/password", "user/reset_password");
});

// 客户端心跳
Route::rule("heartbeat/[:token]", "user/heartbeat");

// 获取变量内容
Route::rule("var/[:name]", "index/var");