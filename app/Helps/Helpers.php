<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-06
 * Time: 16:01
 */

use Illuminate\Support\Facades\Auth;

if (!function_exists('getLoginUserId')) {
    /**
     * @return int|null
     */
    function getLoginUserId()
    {
        return Auth::id();
    }
}

if (!function_exists('getLoginUserName')) {
    function getLoginUserName()
    {
        $user = Auth::user();
        return $user ? $user['username'] : '';
    }
}

if (!function_exists('getDict')) {
    function getDict($name, $key = '', $field = 'value') {
        $dictValueModel = new \App\Model\DictValueModel();
        if ($key === null || $key === '') {
            return $dictValueModel->getByName($name)->items();
        } else {
            $dict = $dictValueModel->getByName($name)->getByKey($key)->first();
            return $dict ? $dict[$field] : '';
        }
    }
}

if (!function_exists('parseBowser')) {
    function parseBowser($ua) {
        preg_match("/(Chrome|Firefox)\/(\.|\d)*/", $ua, $match);
        return $match ? $match[0] : $ua;
    }
}

if (!function_exists('parseUrl')) {
    function parseUrl($url) {
        if (!strstr($url, 'http')) {
            $url = url($url);
        }
        return $url;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return Auth::user()->isAdmin();
    }
}
