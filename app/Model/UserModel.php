<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2019/1/4
 * Time: 10:51
 */

namespace App\Model;


class UserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    /**
     * get user by name
     * @param $name
     * @return $this
     */
    public function getByName($name)
    {
        $this->where[] = ['users.name', '=', $name];
        return $this;
    }

    /**
     * get user by email
     * @param $email
     * @return $this
     */
    public function getByEmail($email)
    {
        $this->where[] = ['users.email', '=', $email];
        return $this;
    }

    /**
     * get user by name
     * @param $name
     * @return $this
     */
    public function searchByName($name)
    {
        $this->where[] = ['users.name', 'like', '%'.$name.'%'];
        return $this;
    }

    /**
     * get user by email
     * @param $email
     * @return $this
     */
    public function searchByEmail($email)
    {
        $this->where[] = ['users.email', 'like', '%'.$email.'%'];
        return $this;
    }

    /**
     * get user by id
     * @param $id
     * @return $this
     */
    public function getById($id)
    {
        $this->where[] = ['users.id', '=', $id];
        return $this;
    }

    /**
     * get users by role id
     * @param $roleId
     * @return $this
     */
    public function getByRoleId($roleId)
    {
        $this->where[] = ['users.role_id', '=', $roleId];
        return $this;
    }
}
