<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-12-05
 * Time: 0:24
 */

namespace App\Model;


class DictValueModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'dict_value';
        $this->orderBy = ['dict_value.id' => 'asc'];
    }

    /**
     * @param $id
     * @return $this
     */
    public function getById($id)
    {
        $this->where[] = ['dict_value.id', '=', $id];
        return $this;
    }

    /**
     * @param $dictId
     * @return $this
     */
    public function getByDictId($dictId)
    {
        $dict = (new DictModel())->getById($dictId)->first();
        $this->getByName($dict['name']);
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function getByName($name)
    {
        $this->where[] = ['dict_value.name', '=', $name];
        return $this;
    }


    /**
     * @param $key
     * @return $this
     */
    public function getByKey($key)
    {
        $this->where[] = ['dict_value.key', '=', $key];
        return $this;
    }
}