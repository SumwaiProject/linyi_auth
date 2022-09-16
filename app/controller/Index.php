<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    /**
     * @Route Index/index
     */
    public function index()
    {
        return "Hello, This is `auth_linyi_new` project";
    }

}
