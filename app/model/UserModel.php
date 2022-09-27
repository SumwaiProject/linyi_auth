<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UserModel extends Model
{

    protected $name = 'user';
    protected static $salt = '';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'update_at';

    /**
     * user -> verify -> password
     * 检查输入的密码是否正确
     *
     * @param  string $password 输入的密码
     * @return bool
     */
    public function _verify_password(string $password = '')
    {
        return md5($password . $this->salt) == $this->password;
    }

    /**
     * encrypt password
     *
     * @param  string $password 
     * @return void
     */
    public static function encrypt($password = '')
    {
        return md5($password . self::salt());
    }

    /**
     * once to get salt
     *
     * @return void
     */
    public static function salt()
    {
        return self::$salt ? self::$salt : self::$salt = salt();
    }

    /**
     * Get UserModel from token
     *
     * @param  string $token token string
     * @return UserModel
     */
    public static function fromToken($token = '')
    {
        return self::where('token', $token)->findOrEmpty();
    }
}
