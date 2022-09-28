<?php

declare(strict_types=1);

namespace app\middleware;

use app\BaseController;
use Mail;
use think\facade\Cache;

class verify extends BaseController
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // TODO 验证码发送和验证

        try {
            $verify = $request->param('verify');
            $email = $request->param('email');
            $key = $key;
            $this->validate($request->param(), [
                'email|邮箱' => 'require|email',
                'verify|验证码' => 'min:6|max:6|number'
            ]);
        } catch (\Exception $e) {
            return $this->msg($e->getMessage(), -1);
        }


        $dict = ['signup' => '注册', 'signin' => '登录'];
        $action = $request->action();
        $action_string = $dict[$action];
        if (!$request->param('verify')) {
            if (Cache::has($key)) {
                return $this->msg("发送{$action_string}验证码失败", -1);
            }
            $code = rand(100000, 999999);
            // 设置五分钟内有效的验证码
            Mail::send($email, '请确认您的' . $action_string . '验证码', '您的' . $action_string . '验证码为: ' . $code . ', 五分钟内有效。');
            Cache::set($key, $code, 300);
            return $this->msg("发送{$action_string}验证码成功", 100);
        }

        if (Cache::get($key) != $verify) {
            return $this->msg('验证码错误，请重试', -1);
        }

        Cache::delete($key);

        return $next($request);
    }
}
