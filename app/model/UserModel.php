<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UserModel extends Model
{

    protected $name = 'user';


    /**
     * user -> verify -> password
     * 检查输入的密码是否正确
     *
     * @param  string $password 输入的密码
     * @return bool
     */
    public function _verify_password(string $password = ''){
        return md5($password . $this->salt) === $this->password;
    }
}
