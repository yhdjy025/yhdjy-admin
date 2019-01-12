<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2019/1/4
 * Time: 11:23
 */

namespace App\Model;


class UserRoleModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'user_role';
    }

    /**
     * get role by name
     * @param $name
     * @return $this
     */
    public function getByName($name)
    {
        $this->where[] = ['user_role.name', '=', $name];
        return $this;
    }

    /**
     * get enable role
     * @return $this
     */
    public function getByEnable()
    {
        $this->where[] = ['user_role.status',  '=',  1];
        return $this;
    }

    /**
     * get role by id
     * @param $id
     * @return $this
     */
    public function getById($id)
    {
        $this->where[] = ['user_role.id', '=', $id];
        return $this;
    }
}
