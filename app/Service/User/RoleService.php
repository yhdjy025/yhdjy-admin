<?php

namespace App\Service\User;

use App\Exceptions\AppException;
use App\Model\UserModel;
use App\Model\UserRoleModel;
use App\Service\BaseService;

class RoleService extends BaseService
{
    public function getRoleModel()
    {
        return new UserRoleModel();
    }

    /**
     * edit role
     * @param $params
     * @return bool|int
     * @throws AppException
     */
    public function editRole($params)
    {
        $name = $params['name'] ?? '';
        if (empty($name)) {
            throw new AppException('参数错误！');
        }
        $data = [
            'name'        => $params['name'] ?? '',
            'desc'        => $params['desc'] ?? '',
            'permissions' => '',
            'user_num'    => 0,
            'status'      => $params['status'] ?? 1,
            'created_by'  => getLoginUserId(),
            'updated_by'  => getLoginUserId(),
            'created_at'  => time(),
            'updated_at'  => time()
        ];
        return $this->getRoleModel()->edit($data, $params['id'] ?? 0);
    }

    /**
     * set permissions
     * @param $roleId
     * @param $permissions
     * @return number
     */
    public function setPermissions($roleId, $permissions)
    {
        $data = implode(',', $permissions);
        return $this->update('user_role', ['permissions' => $data], $roleId);
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     * @throws AppException
     */
    public function delEmailById($id)
    {
        $users = (new UserModel())->getByRoleId($id)->items();
        if ($users->isNotEmpty()) {
            throw new AppException('该角色下有用户禁止删除！');
        }
        return $this->getEmailModel()->delById($id);
    }
}
