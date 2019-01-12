<?php
/**
 *  Message response service
 *
 * @author itas
 */

namespace App\Service\Common;

use Illuminate\Support\Facades\Response;

class ResponseService
{
    const SUCCESS = '200';
    const FAIL = '110';
    const PARAGRAM_ERROR = '10010';
    const HTTP_REQUEST_METHOD_ERROR = '12306';
    const UNKNOW_ERROR = '10086';
    const DATA_ADD_SUCCESS = '2001';
    const DATA_UPDATE_SUCCESS = '2002';
    const USER_FAIL = '3001';
    const USER_FORBID = '3002';
    const USER_UNAUTHORIZED = '3003';

    const API_SUCCESS = '10113';
    const API_PARAGRAM_ERROR = '10027';
    const API_DATA_EXISTS = '10043';
    const API_ADD_SUCCESS = '10049';
    const API_ADD_FAIL = '10050';

    const GOODS_BRAND_UNKNOWN = '4001';
    const GOODS_CATE_UNKNOWN = '4101';
    const GOODS_P_CATE_UNKNOWN = '4102';
    const GOODS_CATE_LEVEL_MAX = '4103';
    const GOODS_COMMON_UNKNOWN = '4201';
    const GOODS_UNKNOWN = '4202';

    /**
     * return success result info
     *
     * @author itas
     *
     * @param  array $data
     * @param  string $message
     * @param  string $url
     *
     * @return mixed
     */
    public static function success($data, $message = '', $url = '')
    {
        $returnData['code'] = '200';

        if (!empty($message)) {
            $returnData['message'] = $message;
        } else {
            $message = self::getErrorMessage(200);
            $returnData['message'] = $message;
        }

        $returnData['data'] = $data;
        $returnData['url'] = $url;

        if (request()->ajax() || request('json') == 1) {
            return Response::json($returnData);
        } else {
            if ($url == '') {
                return redirect()->back()->with("success", $message);
            } else {
                return redirect()->to($url)->with("success", $message);
            }

        }
    }

    /**
     * return error result info
     *
     * @author itas
     *
     * @param  string
     * @param  string
     *
     * @return mixed
     */
    public static function error($message = '', $code = '10086')
    {
        $returnData['code'] = $code;

        if (!empty($message)) {
            $returnData['message'] = $message;
        } else {
            $message = self::getErrorMessage($code);
            $returnData['message'] = $message;
        }

        $returnData['data'] = '';

        if (request()->ajax() || request('json') == 1) {
            return Response::json($returnData);
        } else {
            return redirect()->back()->withInput()->withErrors($message);
        }
    }

    public static function jumpError($code)
    {
        return redirect('index/err?code=' . $code);
    }

    public static function api($data, $code = '200', $message = '')
    {
        $returnData['data'] = $data;
        $returnData['code'] = $code;

        if (!empty($message)) {
            $returnData['message'] = $message;
        } else {
            $message = self::getErrorMessage($code);
            $returnData['message'] = $message;
        }
        return Response::json($returnData);
    }

    public static function openApi($data = [], $code = '10113', $message = '')
    {
        $returnData['errCode'] = $code;

        if (isset($message)) {
            $returnData['errMsg'] = $message;
        } else {
            $message = self::getErrorMessage($code);
            $returnData['errMsg'] = $message;
        }

        if ($code == '10113' || $code == '200') {
            $returnData['status'] = true;
        } else {
            $returnData['status'] = false;
        }

        $returnData['data'] = $data;
        return Response::json($returnData);
    }


    public static function errorMessage()
    {
        return [
            self::SUCCESS => '数据获取成功!',
            self::FAIL => '数据获取失败!',
            self::PARAGRAM_ERROR => '参数错误!',
            self::HTTP_REQUEST_METHOD_ERROR => '请求方法错误',
            self::UNKNOW_ERROR => '未知错误',
            self::USER_FAIL => '获取用户信息失败,请重新登录...',
            self::USER_FORBID => '用户已被禁用，如有疑问，请联系管理员处理！',
            self::USER_UNAUTHORIZED => '抱歉，你没有权限访问该页面！',

            self::API_SUCCESS => 'api数据获取成功',
            self::API_PARAGRAM_ERROR => 'api参数错误',
            self::API_DATA_EXISTS => '数据已存在',
            self::API_ADD_SUCCESS => '数据添加成功',
            self::API_ADD_FAIL => '数据添加失败',
        ];
    }

    public static function getErrorMessage($code = '')
    {
        $errorMessage = self::errorMessage();

        return isset($errorMessage[$code]) ? $errorMessage[$code] : '';
    }

    public static function apiErrorMessage()
    {
        return [

        ];
    }
}
