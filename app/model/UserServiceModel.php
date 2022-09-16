<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UserServiceModel extends Model
{
    
    protected $name = 'user_service';

    /**
     * user service -> _has
     * 
     * check is user has this service
     *
     * @param  UserModel $user         用户
     * @param  string    $service_name 服务名
     * 
     * @return bool
     */
    public static function _has(UserModel $user, string $service_name = ''){
        if ($user->isEmpty()) {
            return false;
        }
        $service = self::where('user_id', $user->id)->where('service', $service_name)->findOrEmpty();
        if ($service->isEmpty()){
            return false;
        }
        return !expired($service->expire_time);
    }
}
