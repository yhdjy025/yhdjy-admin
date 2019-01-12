<?php

namespace App\Service\User;

use App\Exceptions\AppException;
use App\Model\UserModel;
use Illuminate\Support\Facades\DB;
use App\Service\BaseService;

class UserService extends BaseService
{


    public function getUserModel()
    {
        return new UserModel();
    }

    /**
     * 获取用户信息
     * @param $id
     * @return array
     */
    public function getUser($id)
    {
        return $this->getUserModel()->getById($id)->first();
    }

    /**
     * 用户唯一性校验
     * @param $email
     * @param $name
     * @throws AppException
     */
    public function validUser($email, $name)
    {
        $user = DB::table('users')->select(['id', 'name', 'email'])
            ->where(function ($query) use ($email, $name) {
                $query->where('email', $email)->orWhere('name', $name);
            })->orderBy('id', 'asc')->first();

        if (!empty($user)) {
            if ($user['name'] == $name) {
                throw new AppException('该用户名已存在！');
            } else {
                throw new AppException('该电子邮箱已存在！');
            }
        }
    }

    /**
     * 编辑用户
     * @param $uid
     * @param $data
     * @return bool|int
     */
    public function editUser($uid, $data)
    {
        return $this->getUserModel()->edit($data, $uid);
    }
}
