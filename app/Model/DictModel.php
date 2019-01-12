<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/4
 * Time: 19:11
 */

namespace App\Model;


class DictModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'dict';
    }

    /**get site by site
     * @param $name
     * @return $this
     */
    public function getByName($name)
    {
        $name = strtoupper($name);
        $this->where[] = ['dict.name', '=', $name];
        return $this;
    }

    /**
     * get site by id
     * @param $id
     * @return $this
     */
    public function getById($id)
    {
        $this->where[] = ['dict.id', '=', $id];
        return $this;
    }

    public function getByIds($ids)
    {
        $this->where[] = ['dict.id', 'in', $ids];
        return $this;
    }

    /**
     *  edit site account
     * @param $data
     * @param $id
     * @return bool|int
     */
    public function edit($data, $id = null)
    {
        if (empty($id)) {
            $dict = $this->firstWhere($this->table, ['name' => strtoupper($data['name'])]);
            if (!empty($dict)) {
                throw new AppException('名称已经被使用！');
            }
            $data['created_at'] = time();
        }
        return parent::edit($data, $id);
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     * @throws \Throwable
     */
    public function delById($id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }
        try {
            DB::transaction(function () use ($id) {
                $dict = $this->getByIds($id)->items();
                if ($dict) {
                    $this->deleteWhere('dict_value', ['name', 'in', $dict->pluck('name')->all()]);
                    $this->deleteWhere($this->table, ['id', 'in', $id]);
                }
            });
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}