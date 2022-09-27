<?php
declare (strict_types = 1);

namespace app\middleware;

class verify
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
        return $next($request);
    }
}
