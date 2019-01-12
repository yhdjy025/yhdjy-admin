<?php

namespace App\Policies;

use App\Service\Common\CommonUtils;
use App\Service\System\NodeService;
use App\User;

class PermissionPolicy
{

    /**
     * 路由层检查权限
     * @param User $user
     * @param string $route
     * @return boolean true/false
     */
    public function route(User $user, $route='/')
    {
        if (isAdmin()) return true;

        $route = CommonUtils::wipeBracket($route);
        $node = NodeService::getInstance()->getNode($route);
        // 无需权限控制
        if (!empty($node) && $node['is_auth'] == 0) {
            return true;
        }

        if ($user->isPermissible($route)) {
            return true;
        }
        return false;
    }

    /**
     * 检查用户是否有操作权限
     * 基于用户角色的权限
     * 使用Gate来检查，对应 permission.check
     * @param  User       $user     当前登录对像
     * @param  string     $operator 检查的操作
     * @return Boolean 是否有权限
     */
    public function check(User $user, $operator='/')
    {
        if ($user->isPermissible($operator)) {
            return true;
        }
        return false;
    }
}
