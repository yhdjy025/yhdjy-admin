<?php

namespace App;

use App\Service\Common\PermissionsService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 确定是不是系统管理员
     * @author leesenlen
     * @date   2017-09-18
     * @return boolean    true/false
     */
    public function isAdmin() {
        if (in_array($this->getAuthIdentifier(), config('system.admin'))) {
            return true;
        }
        return false;
    }

    /**
     * 检查账号有没有权限
     * @author hky
     * @date   2018-06-15
     * @param $node
     * @return bool
     */
    public function isPermissible($node) {
        if (empty($node)) return false;
        $power = $this->permissions();
        $power = explode(',', $power);
        if(in_array($node, $power)){
            return true;
        }
        return false;
    }

    /**
     * 获取拥有的权限列表
     * @author leesenlen
     * @date   2017-09-19
     */
    public function permissions() {
        if (!isset($this->permissionList)) {
            $perm = PermissionsService::getAllPermissions($this->getAuthIdentifier());
            $this->permissionList = $perm['power'];
        }
        return $this->permissionList;
    }
}
