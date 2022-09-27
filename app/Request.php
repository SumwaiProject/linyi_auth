<?php

namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    /**
     * decode request php://input [aes data]
     *
     * @return void
     */
    public function decode()
    {
        $input = $this->input;
        $data = decrypt($input, true);

        if (!is_array($data)) {
            return;
        }
        foreach ($data as $k => $v) {
            $this->set($k, $v);
        }
    }

    /**
     * Set param
     *
     * @param  string $k key
     * @param  string $v value
     * @return void
     */
    protected function set($k = '', $v = '')
    {
        $this->param[$k] = $v;
    }
}
