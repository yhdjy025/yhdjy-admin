<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-14
 * Time: 19:47
 */

namespace App\Service\System;

use App\Exceptions\AppException;
use App\Model\DictModel;
use App\Model\DictValueModel;
use App\Service\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DictService extends BaseService
{
    /**
     * @return DictModel
     */
    public function getDictModel()
    {
        return new DictModel();
    }

    /**
     * @return DictValueModel
     */
    public function getDictValueModel()
    {
        return new DictValueModel();
    }
    /**
     *  edit site account
     * @param $params
     * @return bool|int
     */
    public function editDict($params)
    {
        $data = [
            'name'       => strtoupper($params['name']),
            'title'       => $params['title'] ?? ''
        ];
        return $this->getDictModel()->edit($data, $params['id'] ?? null);
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     * @throws \Throwable
     */
    public function delDictById($id)
    {
        return $this->getDictModel()->delById($id);
    }

    /**
     * 保存键值
     * @param $params
     * @return bool|int
     */
    public function editValue($params)
    {
        $data = [
            'name' => $params['name'],
            'key' => $params['key'],
            'value' => $params['value']
        ];
        if (empty($params['id'])) {
            $data['created_at'] = time();
        }
        return $this->getDictValueModel()->edit($data, $params['id'] ?? null);
    }

    /**
     * @param $id
     * @return int|mixed
     */
    public function delValueById($id)
    {
        return $this->getDictValueModel()->delById($id);
    }
}