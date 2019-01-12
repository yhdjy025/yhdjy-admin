<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-12-15
 * Time: 12:33
 */

namespace App\Service\System;


use App\Model\CountryModel;
use App\Service\BaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class CountryService extends BaseService
{
    public function getCountryModel()
    {
        return (new CountryModel());
    }

    /**
     *  edit country account
     * @param $params
     * @return bool|int
     */
    public function editCountry($params)
    {
        $data = [
            'name'    => $params['name'] ?? '',
            'short_name' => $params['short_name'] ?? '',
            'icon'   => $params['icon'] ?? ''
        ];
        Cache::forget('all_countries');
        return $this->getCountryModel()->edit($data, $params['id'] ?? null);
    }

    /**
     * del by id
     * @param $id
     * @return int|mixed
     */
    public function delCountryById($id)
    {
        Cache::forget('all_countries');
        return $this->getCountryModel()->delById($id);
    }

    /**
     * get all country
     * @return \Illuminate\Support\Collection
     */
    public static function getAllCountries()
    {
        return Cache::rememberForever('all_countries', function () {
            return (new CountryModel())->select()->items();
        });
    }

    /**
     * get country
     * @param $id
     * @param string $field
     * @return mixed|string
     */
    public static function getCountry($id, $field)
    {
        $country = (new CountryModel())
            ->getById($id)
            ->first();
        return $field ? ($country[$field] ?? '') : $country;
    }

    /**
     *get selected countryId
     */
    public static function getSelectedCountryId()
    {
        $countryId = Cookie::get('selected_country');
        if (empty($countryId)) {
            $countryId = 1;
            Cookie::queue('selected_country', $countryId, 86400 * 30);
        }
        return $countryId;
    }

    /**
     * @param $countryId
     */
    public static function setCountry($countryId)
    {
        Cookie::queue('selected_country', $countryId, 86400 * 30);
    }
}