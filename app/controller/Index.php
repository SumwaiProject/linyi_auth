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
        return "Hello, This is `auth_linyi_new` project";
    }

    /**
     * test send email
     *
     * @return void
     */
    public function sendmail(){
        // return Mail::send('admin@lim1.cn', "Hello", '<a href="https://sumwai.cn/">查看详情</a>') ? 'send mail success' : 'failed to send mail';
    }

}
