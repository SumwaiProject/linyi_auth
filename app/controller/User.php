<?php

declare(strict_types=1);

namespace app\controller;

use \app\BaseController;

/**
 * 用户控制器
 */
class User extends BaseController
{
    /**
     * user -> sign in
     * 
     * @param  string $email    邮箱
     * @param  string $password 密码
     * @param  string $verify   验证码
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function signin(string $email = '', string $password = '', string $verify = '')
    {
    }

    /**
     * user -> sign up
     *
     * @param  string $email    邮箱
     * @param  string $password 密码
     * @param  string $verify   验证码
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function signup(string $email = '', string $password = '', string $verify = '')
    {
    }

    /**
     * user -> heartbeat
     * 客户端心跳
     *
     * @param  string $token 用户token
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function heartbeat(string $token = '')
    {
    }

    /**
     * user -> service
     * 查询用户服务状态
     * 
     * @param string $token 用户token
     * @param string $service_name 服务名称
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function service(string $token = '')
    {
    }

    /**
     * user -> online
     * 查询用户是否在线
     * 
     * @param string $token 用户token
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function online(string $token = '')
    {
    }

    /**
     * 用户使用cdk兑换会员
     * 
     * @param string $token 用户token
     * @param string $cdk 兑换cdk
     *
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function cdk(string $token = '', string $cdk = '')
    {
    }

    /**
     * user -> forget -> password
     * 用户忘记密码
     * 
     * @param  string $email 用户邮箱账号
     * @return Json
     */
    function forget_password(string $email = ''){

    }

    /**
     * user -> reset -> password
     *
     * @param  string $email    用户邮箱
     * @param  string $token    修改密码token
     * @param  string $password 新密码
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function reset_password(string $email = '', string $token = '', string $password = ''){

    }
}
