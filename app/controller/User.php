<?php

declare(strict_types=1);

namespace app\controller;

use \app\BaseController;
use app\model\KamiModel;
use app\model\UserModel;
use app\model\UserServiceModel;
use app\Request;
use think\facade\Cache;
use think\facade\Env;

/**
 * 用户控制器
 */
class User extends BaseController
{

    /**
     * 注册验证码
     * @var boolean
     */
    protected $signup_verify = false;
    /**
     * 登录验证码
     * @var boolean
     */
    protected $signin_verify = false;

    /**
     * user -> sign in 登录
     * 
     * @param  string $email    邮箱
     * @param  string $password 密码
     * @param  string $verify   验证码
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function signin(Request $request, string $email = '', string $password = '')
    {
        // 验证输入参数
        try {
            $validate = [
                'email|邮箱' => 'require|email',
                'password|密码' => 'require|min:6|max:32|regex:/[^\x{4E00}-\x{9FFF}]/u',
                'verify|验证码' => 'number|min:6|max:6'
            ];
            $this->validate($request->param(), $validate);
        } catch (\Exception $e) {
            return $this->msg($e->getMessage(), -1);
        }

        $UserModel = UserModel::where('email', $email)->findOrEmpty();

        if ($UserModel->isEmpty()) {
            return $this->msg('邮箱未注册', -1);
        }

        if (!$UserModel->_verify_password($password)) {
            return $this->msg('密码错误', -1);
        }

        // 
        cache($UserModel->token, null);

        $UserModel->token = $UserModel->salt();

        $UserModel->save();
        cache($UserModel->salt(), time(), Env::get('user.online', 600));

        return $this->success([
            'token' => $UserModel->salt()
        ]);
    }

    /**
     * user -> sign up 注册
     *
     * @param  string $email    邮箱
     * @param  string $password 密码
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function signup(Request $request, string $email = '', string $password = '')
    {
        // 验证输入参数
        try {
            $validate = [
                'email|邮箱' => 'require|email',
                'password|密码' => 'require|min:6|max:32|regex:/[^\x{4E00}-\x{9FFF}]+/u',
                'verify|验证码' => 'number|min:6|max:6'
            ];
            $this->validate($request->param(), $validate);
        } catch (\Exception $e) {
            return $this->msg($e->getMessage(), -1);
        }


        // 检查邮箱是否已被注册
        if (!UserModel::where('email', $email)->findOrEmpty()->isEmpty()) {
            return $this->msg('邮箱已被注册', -1);
        }

        // 注册账号
        UserModel::create([
            'email' => $email,
            'password' => UserModel::encrypt($password, salt()),
            'salt' => UserModel::salt(),
            'status' => 0,
            'create_ip' => $request->ip(),
        ]);

        return $this->msg('注册成功');
    }

    /**
     * user -> heartbeat 心跳
     *
     * @param  string $token 用户token
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Json
     */
    function heartbeat(string $token = '')
    {
        if (Cache::has($token)) {
            // 为用户续上在线时间
            Cache::set($token, time(), Env::get('user.online', 600));
            return $this->success();
        }
        return $this->msg('', -1);
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
    function service(string $token = '', $service_name = '')
    {
        $UserModel = UserModel::fromToken($token);

        if ($UserModel->isEmpty()) {
            return $this->msg('已掉线', -1);
        }

        if (!Cache::has($UserModel->token)) {
            return $this->msg('已掉线', -1);
        }

        $UserServiceModel = new UserServiceModel();

        $UserServiceModel->where('expire', '>=', time());
        $UserServiceModel->where('user_id', $UserModel->id);

        if ($service_name) {
            if (UserServiceModel::where('user_id', $UserModel->id)->where('expire', '>=', time())->where('service', $service_name)->findOrEmpty()->isEmpty()) {
                return $this->msg('无服务', -1);
            } else {
                return $this->success();
            }
        }

        return $this->success($UserServiceModel->field(['service', 'expire'])->select());
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
        return Cache::has($token) ? $this->success() : $this->msg('已掉线', -1);
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
        $UserModel = UserModel::fromToken($token);

        if ($UserModel->isEmpty() || !Cache::has($token)) {
            return $this->msg('已掉线', -1);
        }

        $KamiModel = KamiModel::where('value', $cdk)->where('status', 0)->findOrEmpty();
        if ($KamiModel->isEmpty()) {
            return $this->msg('卡密不存在', -1);
        }

        $KamiModel->status = 1;
        $KamiModel->save();

        $UserServiceModel = UserServiceModel::where('user_id', $UserModel->id)->where('service', $KamiModel->service)->findOrEmpty();

        if ($UserServiceModel->isEmpty()) {
            $expire = time() + 86400 * $KamiModel->days;
            UserServiceModel::create([
                'user_id' => $UserModel->id,
                'service' => $KamiModel->service,
                'expire' => $expire,
            ]);
            return $this->success(['time' => $expire, 'datetime' => date('Y-m-d H:i:s', $expire)]);
        }

        $UserServiceModel->expire = $UserServiceModel->expire < time() ? time() + $KamiModel->days + 86400 : $UserServiceModel->expire + $KamiModel->days * 86400;
        $UserServiceModel->save();
    }

    /**
     * user -> forget -> password
     * 用户忘记密码
     * 
     * @param  string $email 用户邮箱账号
     * @return Json
     */
    function forget_password(string $email = '')
    {
        // TODO send email
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
    function reset_password(string $email = '', string $token = '', string $password = '')
    {
        // TODO 重置密码
    }
}
