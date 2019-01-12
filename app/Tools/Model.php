<?php
/**
 * Model 工具类
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/4
 * Time: 15:51
 */

namespace App\Tools;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Model
{
    /**
     * 增加where条件
     * @param $query    Builder
     * @param $where    array
     * @return Builder
     */
    public function appendConditions($query, $where)
    {
        if (empty($where)) {
            return $query;
        }
        if (is_string(current($where)) && isset($where[0])) {
            if (count($where) == 2) {
                list($field, $value) = $where;
                $op = '=';
            } elseif (count($where) == 3) {
                list($field, $op, $value) = $where;
            } else {
                return $query;
            }
            $query = $this->whereType($query, $field, $op, $value);
        } else {
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    if (count($value) == 2) {
                        list($field, $value) = $value;
                        $op = '=';
                    } elseif (count($value) == 3) {
                        list($field, $op, $value) = $value;
                    } else {
                        continue;
                    }
                } else {
                    $op = '=';
                }
                $query = $this->whereType($query, $field, $op, $value);
            }
        }
        return $query;
    }

    /**
     * @param $query Builder
     * @param $field
     * @param $op
     * @param $value
     * @return mixed Builder
     */
    private function whereType($query, $field, $op, $value)
    {
        if ('raw' == $op) {
            $query->whereRaw($value);
        } elseif ('in' == $op) {
            $query->whereIn($field, $value);
        } elseif ('notin' == $op) {
            $query->whereNotIn($field, $value);
        } elseif ('null' == $op) {
            $query->whereNull($field, $value);
        } elseif ('notnull' == $op) {
            $query->whereNotNull($field, $value);
        } else {
            $query->where($field, $op, $value);
        }
        return $query;
    }

    /**
     * 排序方式
     * @param $query    Builder
     * @param $orderBy  array
     * @return Builder  Builder
     */
    public function orderBy($query, $orderBy)
    {
        if (empty($orderBy)) {
            return $query;
        }
        foreach ($orderBy as $field => $value) {
            $query->orderBy($field, $value);
        }
        return $query;
    }

    /**
     * 取得相关数据，如果$pageNum不为空时，分页获取
     * @param string $table
     * @param array $where
     * @param array $order
     * @param null $perPage
     * @param array $columns
     * @return void|\Illuminate\Support\Collection
     */
    public function selectWhere($table, array $where = [], array $order = [], $perPage = null, $columns = ['*'])
    {
        if (empty($table)) {
            return;
        }

        $query = DB::table($table);
        $this->appendConditions($query, $where);
        $this->orderBy($query, $order);

        if (empty($perPage)) {
            $datas = $query->select($columns)->get();
        } else {
            $datas = $query->select($columns)->paginate($perPage);
        }
        return $datas;
    }

    /**
     * find all entities by given criteria.
     * @param $table
     * @param array $where
     * @param array $columns
     * @return void|\Illuminate\Support\Collection
     */
    public function findWhere($table, array $where, $columns = ['*'])
    {
        if (empty($table)) {
            return;
        }

        $query = DB::table($table);
        $this->appendConditions($query, $where);
        $rslt = $query->select($columns)->get();
        return $rslt;
    }

    /**
     * find first entity by given criteria.
     * @param $table
     * @param array $where
     * @param array $columns
     * @return void|\Illuminate\Database\Eloquent\Model|object|\Illuminate\Database\Query\Builder|NULL
     */
    public function firstWhere($table, array $where, $columns = ['*'])
    {
        if (empty($table)) {
            return;
        }

        $query = DB::table($table);
        $this->appendConditions($query, $where);
        $rslt = $query->select($columns)->first();
        return $rslt;
    }

    /**
     * Delete multiple entities by given criteria.
     * @param $table
     * @param $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object|void
     */
    public function find($table, $id, $columns = ['*'])
    {
        if (empty($table) || empty($id)) {
            return;
        }

        $rslt = DB::table($table)->select($columns)->where('id', $id)->first();
        return $rslt;
    }

    /**
     * Delete multiple entities by given criteria.
     * @param $table
     * @param array $where
     * @return int
     */
    public function deleteWhere($table, array $where)
    {
        if (empty($table)) {
            return;
        }

        $query = DB::table($table);
        $this->appendConditions($query, $where);
        $deleted = $query->delete();
        return $deleted;
    }

    /**
     * Update a entity in service by where
     * @param $table
     * @param array $attributes
     * @param array $where
     * @return mixed
     */
    public function updateByWhere($table, array $attributes, array $where = [])
    {

        if (empty($table) || empty($attributes)) {
            return;
        }

        $query = DB::table($table);
        $this->appendConditions($query, $where);
        $updated = $query->update($attributes);
        return $updated;
    }

    /**
     * 删除数据
     * @param $table
     * @param $id
     * @return number
     */
    public function delete($table, $id)
    {
        $rslt = DB::Table($table)->where('id', $id)->delete();
        return $rslt;
    }

    /**
     * 插入数据
     * @param $table
     * @param array $values
     * @return bool
     */
    public function insert($table, array $values)
    {
        $rslt = DB::table($table)->insert($values);
        return $rslt;
    }

    /**
     * 插入数据
     * @param $table
     * @param array $values
     * @return int $id
     */
    public function insertGetId($table, array $values)
    {
        $rslt = DB::table($table)->insertGetId($values);
        return $rslt;
    }

    /**
     * 更新数据
     * @param array $attributes
     * @param $id
     * @return number
     */
    public function update($table, array $attributes, $id)
    {
        $rslt = DB::Table($table)->where('id', $id)->update($attributes);
        return $rslt;
    }

    /**
     * 更新或者创建
     * @param $table
     * @param array $attributes
     * @param array $values
     * @return void|number
     */
    public function updateOrCreate($table, array $attributes, array $values = [])
    {

        if (empty($table) || empty($values)) {
            return false;
        }

        $id = '';

        // NULL数据，不处理
        foreach ($values as $col => $val) {
            if (is_null($val)) {
                unset($values[$col]);
            }
        }

        if (array_key_exists('id', $values)) {
            if (empty($values['id']) || empty(trim($values['id']))) {
                unset($values['id']);
            } else {
                $id = $values['id'];
            }
        } else if (!empty($attributes)) {
            if (isset($attributes['id'])) {
                $id = $attributes['id'];
            } else {
                $query = DB::table($table);
                $this->appendConditions($query, $attributes);
                $id = $query->value('id');
            }
        }

        if (empty($id)) {
            $ret = DB::table($table)->insert($values);
        } else {
            $ret = DB::table($table)->where('id', $id)->update($values);
        }

        return $ret;
    }
}
