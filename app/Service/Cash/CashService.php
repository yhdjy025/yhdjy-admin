<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2019-01-10
 * Time: 20:59
 */

namespace App\Service\Cash;


use App\Model\CashModel;
use App\Service\BaseService;

class CashService extends BaseService
{
    public function getCashModel()
    {
        return new CashModel();
    }

    /**
     * edit cash
     * @param $params
     * @return bool|int
     */
    public function editCash($params)
    {
        $data = [
            'email'    => $params['email'] ?? '',
            'site_id' => $params['site_id'] ?? '',
            'status'   => $params['status'] ?? 0,
            'type'  => $params['type'] ?? 1,
            'amount' => $params['amount'] ?? 0,
            'country' => $params['country'] ?? 0,
            'currency' => $params['currency'] ?? 1,
            'content' => $params['content'] ?? ''
        ];
        if (empty($params['id'])) {
            $data['created_at'] = time();
            $data['uid'] = getLoginUserId();
        }
        return $this->getCashModel()->edit($data, $params['id'] ?? null);
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     */
    public function delCashById($id)
    {
        return $this->getCashModel()->delById($id);
    }
}