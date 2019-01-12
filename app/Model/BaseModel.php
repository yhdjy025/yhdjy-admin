<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/4
 * Time: 15:21
 */

namespace App\Model;


use App\Exceptions\AppException;
use App\Tools\Model;
use Illuminate\Support\Collection;

class BaseModel extends Model
{
    /**
     * @var Collection
     */
    protected $items;

    protected $table;

    protected $where = [];

    protected $orderBy;

    protected $columns = ['*'];

    protected $callBacks = [];

    public function __construct()
    {
        $this->items = new Collection();
    }

    /**
     * init query
     * @return \Illuminate\Database\Query\Builder
     */
    public function initQuery()
    {
        return $query = \DB::table($this->table);
    }

    /**
     * select email
     * @param null $perPage
     * @param array $columns
     * @return $this
     */
    public function select($perPage = null, $columns = null)
    {
        $query = $this->initQuery();
        if (method_exists($this, 'beforeSelect')) {
            $this->beforeSelect($query);
        }
        if (!empty($this->callBacks)) {
            foreach ($this->callBacks as $callBack) {
                $query = $callBack($query);
            }
        }
        $this->appendConditions($query, $this->where);
        $this->orderBy($query, $this->orderBy);
        if (empty($columns)) {
            $columns = $this->columns;
        }
        if (empty($perPage)) {
            $this->items = $query->select($columns)->get();
        } else {

            $this->items    = $query->select($columns)->paginate($perPage);
        }
        $this->where = [];
        $this->orderBy = [];
        return $this;
    }

    /**
     * return all result
     * @return Collection
     */
    public function items()
    {
        if (!empty($this->where)) {
            $this->select();
        }
        return $this->items;
    }

    /**
     * return first result
     * @return mixed
     */
    public function first()
    {
        if (!empty($this->where)) {
            $this->select();
        }
        return $this->items->first();
    }

    /**
     *  edit
     * @param $data
     * @param $id
     * @return bool|int
     */
    public function edit($data, $id = null)
    {
        if (empty($id)) {
            return $this->insert($this->table, $data);
        } else {
            return $this->update($this->table, $data, $id);
        }
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     */
    public function delById($id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }
        $this->deleteWhere($this->table, ['id', 'in', $id]);
    }

    /**
     * sort by
     * @param $sort
     * @return $this
     */
    public function sortBy($sort)
    {
        $this->orderBy = $sort;
        return $this;
    }
}