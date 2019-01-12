<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-06
 * Time: 16:54
 */

namespace App\Tools;


use function Couchbase\defaultDecoder;
use Illuminate\Support\Collection;

class Table
{
    /**
     * @var Collection
     */
    public $columns;
    /**
     * @var Collection
     */
    public $data;

    public $key = 'id';

    const TYPE_TEXT = 1;
    const TYPE_BTN = 2;
    const TYPE_OP = 3;
    const TYPE_CHECKBOX = 4;
    const TYPE_LIINK = 5;
    const TYPE_LABELT = 6;

    public function __construct()
    {
        $this->columns = new Collection();
    }

    /**
     * set key
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * checkbox
     * @param string $field
     * @param array $attrs
     * @return $this
     */
    public function addCheckboxColumn($field = '', $attrs = [])
    {
        if (empty($field)) {
            $field = $this->key;
        }
        $column = [
            'field' => $field,
            'title' => '',
            'type' => self::TYPE_CHECKBOX,
            'attrs' => $attrs
        ];
        $this->columns->push($this->buildColumn($column));
        return $this;
    }

    /**
     * textcloumn
     * @param        $field
     * @param        $title
     * @param string $url
     * @param array $urlParm
     * @param string $format
     * @param array $attrs
     * @return $this
     */
    public function addLinkColumn($field, $title, $url = '', $urlParm = [], $format = '', $attrs = [])
    {
        $column = [
            'field' => $field,
            'title' => $title,
            'url' => $url,
            'urlParm' => $urlParm,
            'type' => self::TYPE_LIINK,
            'attrs' => $attrs,
            'format' => $format
        ];
        $this->columns->push($this->buildColumn($column));
        return $this;
    }

    /**
     * textcloumn
     * @param        $field
     * @param        $title
     * @param string $format
     * @param array $attrs
     * @return $this
     */
    public function addtextColumn($field, $title, $format = '', $attrs = [])
    {
        $column = [
            'field' => $field,
            'title' => $title,
            'format' => $format,
            'type' => self::TYPE_TEXT,
            'attrs' => $attrs
        ];
        $this->columns->push($this->buildColumn($column));
        return $this;
    }

    /**
     * textcloumn
     * @param        $field
     * @param        $title
     * @param string $format
     * @param array $attrs
     * @return $this
     */
    public function addlabelColumn($field, $title, $format = '', $attrs = [])
    {
        $column = [
            'field' => $field,
            'title' => $title,
            'format' => $format,
            'type' => self::TYPE_LABELT,
            'attrs' => $attrs
        ];
        $this->columns->push($this->buildColumn($column));
        return $this;
    }

    /**
     * button column
     * @param $field
     * @param $title
     * @param $url
     * @param array $urlParm
     * @param array $attrs
     * @return $this
     */
    public function addBtnColumn($field, $title, $url, $urlParm = [], $attrs = [])
    {
        $column = [
            'field' => $field,
            'title' => $title,
            'format' => '',
            'type' => self::TYPE_BTN,
            'url' => $url,
            'urlParm' => $urlParm,
            'attrs' => $attrs
        ];
        $this->columns->push($this->buildColumn($column));
        return $this;
    }

    /**
     * operation column
     * @param $ops
     * @return $this
     */
    public function addopColumn($ops)
    {
        $column = [
            'field' => '_op',
            'title' => '操作',
            'type' => self::TYPE_OP,
            'subs' => []
        ];
        $columnObj = $this->buildColumn($column);
        foreach ($ops as $op) {
            $subColumn = [
                'field' => $op['field'],
                'title' => $op['title'],
                'url' => $op['url'],
                'urlParm' => $op['urlParm'] ?? [],
                'attrs' => $op['attrs'] ?? [],
                'type' => self::TYPE_BTN
            ];
            switch ($subColumn['field']) {
                case 'delete':
                    $subColumn['class'] = 'btn-danger';
                    break;
                default :
                    $subColumn['class'] = 'btn-primary';
                    break;
            }

            $columnObj->addSub($this->buildColumn($subColumn));
        }

        $this->columns->push($columnObj);
        return $this;
    }

    /**
     * build table column
     * @param $params
     * @return Column
     */
    private function buildColumn($params)
    {
        $column = new Column();
        foreach ($params as $key => $param) {
            $column->$key = $param;
        }
        return $column;
    }

    /**
     * set data collection
     * @param $data
     * @return mixed
     */
    public function setData($data)
    {
        if (!empty($data)) {
            if (is_array($data)) {
                $this->data = collect($data);
            } elseif (is_object($data)) {
                $this->data = $data;
            }
        }
        return $this;
    }

}
