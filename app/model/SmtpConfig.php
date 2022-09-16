<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class SmtpConfig extends Model
{

    /**
     * Get Smtp Config
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return SmtpConfig
     */
    public static function _get(){
        return self::where('status', 1)->findOrEmpty();
    }

    /**
     * New Smtp Config
     *
     * @param  string $server   
     * @param  string $username 
     * @param  string $password 
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return bool
     */
    public static function _new(string $server = '', string $username = '', string $password = ''){
        return self::insert([
            'server' => $server,
            'username' => $username,
            'password' => $password
        ]);
    }
}
