<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/29
 * Time: 9:34
 */

namespace App\Model;

class MenuModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'menu';
        $this->orderBy = ['menu.sort' => 'asc'];
    }

    /**
     * get by id
     * @param $id
     * @return $this
     */
    public function getById($id)
    {
        $this->where[] = ['menu.id', '=', $id];
        return $this;
    }

    /**
     * get by pid
     * @param $pid
     * @return $this
     */
    public function getByPid($pid)
    {
        $this->where[] = ['menu.pid', '=', $pid];
        return $this;
    }

    /**
     * get enabled
     * @return $this
     */
    public function getEnabled()
    {
        $this->where[] = ['menu.status', '=', 1];
        return $this;
    }

    /**
     * 生成树形
     */
    public function toTree()
    {
        $this->items = collect($this->parseChildren($this->items));
        return $this;
    }

    /**
     * 递归生成树
     * @param $items
     * @param int $pid
     * @return array
     */
    private function parseChildren($items, $pid = 0)
    {
        $result = [];
        foreach ($items as $item) {
            if ($items['pid'] == $pid) {
                $children = $this->parseChildren($items, $items['id']);
                if (!empty($children)) {
                    $item['_children'] = $children;
                }
                $result[] = $item;
            }
        }
        return $result;
    }
}
