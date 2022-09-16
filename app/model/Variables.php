<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Variables extends Model
{
    /**
     * Get Variable
     *
     * @param  string $name 
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Variables
     */
    public static function _get(string $name = '')
    {
        return self::where('name', $name)->findOrEmpty();
    }

    /**
     * Set Varable
     *
     * @param  string $name  
     * @param  string $value 
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return bool
     */
    public static function _set(string $name = '', string $value = '')
    {
        $info = self::_get($name);
        if (!$info->isEmpty()) {
            $info->value = $value;
            $info->save();
            return true;
        }
        return self::insert(['name' => $name, 'value' => $value]);
    }

    /**
     * Get All Variables
     *
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return Variables
     */
    public static function _all()
    {
        return self::select();
    }

    /**
     * Unset Variable
     *
     * @param  string $name 
     * 
     * @author Sumwai <sumwai@qq.com>
     * 
     * @return bool
     */
    public static function _unset(string $name = '')
    {
        return self::where('name', $name)->delete();
    }
}