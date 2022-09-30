<?php
namespace app\controller;

use app\BaseController;
use Mail;

class Index extends BaseController
{
    /**
     * @Route Index/index
     */
    public function index()
    {
        // print decrypt('K4lcRDh+JA2lOYhJJrXYjwyBS0SRGD/dgdk2cAObUvHzZIMyNcsC4ou0DDbR6y8Nz0pzVTIqRNfflV8QSvNbrHNrYfxVqC3NeIjZEagQW5g=');
        return "Hello, This is `auth_linyi_new` project";
    }

    /**
     * test send email
     *
     * @return void
     */
    public function sendmail(string $action = ''){
        if (!in_array($action, ['signin', 'signup', 'login'])){
            abort(404, 'action ' . $action . ' not found');
        }
        return $action;
        // return Mail::send('2811187643@qq.com', "关于某项规定的具体实行", '<a href="https://sumwai.cn/">查看详情</a>') ? 'send mail success' : 'failed to send mail';
        // return Mail::send('2811187643@qq.com', "对于规定的定制情况总结", '<a href="https://sumwai.cn/">查看详情</a>') ? 'send mail success' : 'failed to send mail';
    }

}
