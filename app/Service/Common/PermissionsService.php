<?php

namespace App\Service\Common;

use Illuminate\Support\Facades\Session;
use App\Service\BaseService;
use App\Service\User\UserService;
use App\Service\User\RoleService;

class PermissionsService extends BaseService
{

    /**
     * 根据gid取得permissions
     * @param $id
     * @return array|\App\Services\Ambigous
     */
    public static function getPermissionsById($id)
    {
        $userService = new UserService();
        $roleService = new RoleService();

        $user = $userService->getUser($id);
        $role_id = $user['role_id'];
        $role = $roleService->getRoleModel()->getById($role_id)->first();

        return $role['permissions'] ?? [];
    }

    /**
     * 验证功能权限
     *
     * @author itas
     *
     * @param  string $name
     *
     * @return bool
     */
    public static function verfiyPermissions($name)
    {
        $userid = getLoginUserId();

        $powers = self::getPermissionsById($userid);

        if (in_array($name, $powers) || isAdmin($userid)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用户拥有的所有权限
     *
     * @author itas
     *
     * @param  int $gid
     * @param  bool $rebuild 是否重建session
     *
     * @return array
     */
    public static function getAllPermissions($gid, $rebuild = false)
    {
        /*if (!$rebuild && Session::has('power')) {
            return Session::get('power');
        }*/

        //系统操作权限
        $powers = self::getPermissionsById($gid);
        $power = [
            'power' => $powers,
        ];

        Session::put('power', $power);
        return $power;
    }
}
