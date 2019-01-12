<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-06
 * Time: 17:06
 */

namespace App\Tools;


class Column
{
    public $field;
    public $title;
    public $type = 'text';
    public $format = '';
    public $subs = [];
    public $class = '';
    public $url = '';
    public $attrs = [];

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param Column $column
     */
    public function addSub(Column $column)
    {
        $this->subs[] = $column;
    }

    /**
     * @return array
     */
    public function getSubs(): array
    {
        return $this->subs;
    }

    /**
     * @param array $subs
     */
    public function setSubs(array $subs)
    {
        $this->subs = $subs;
    }

    /**
     * @return array
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    /**
     * @param array $attrs
     */
    public function setAttrs(array $attrs)
    {
        $this->attrs = $attrs;
    }

    /**
     * 格式化字段
     * @param $data
     * @return string
     */
    public function format($data)
    {
        $field = $this->field;
        //格式化
        if ($this->format) {
            $format = explode('.', $this->format);
            switch ($format[0]) {
                case 'DATE':
                    $data[$field] = date('y-m-d H:i', $data[$field]);
                    break;
                case 'DICT':
                    $data[$field] = getDict($format[1], $data[$field]);
                    break;
                default :
                    if (function_exists($this->format)) {
                        $data[$field] = ($this->format)($data[$field]);
                    }
                    break;
            }
        }
        return$data[$field] ??'';
    }

    /**
     * 解析属性
     * @param $data
     * @return string
     */
    public function attrs($data)
    {
        $str = '';
        $copy = '';
        if (!empty($this->attrs)) {
            $attrs = [];
            foreach ($this->attrs as $key => $attr) {
                if (strstr($key, 'data-')) {
                    $attr = $data[$attr] ?? $attr;
                    $str .= 'data-'.$key.'="'.$attr.'"';
                } else {
                    switch ($key) {
                        case 'copy':    //复制的内容
                            if (is_string($attr)) {
                                $copy = $data[$attr] ?? '';
                            } else {
                                $copy = $data[$this->field] ?? '';
                            }
                            break;
                        case 'target':
                            $attrs[$key] = $attr;
                            $str .= 'target="'.$attr.'" ';
                            break;
                        case 'class':
                            $attrs[$key] = $attr;
                            $str .= 'class="'.$attr.'" ';
                            break;
                        default:
                            $attr = $data[$attr] ?? $attr;
                            $str .= 'data-'.$key.'="'.$attr.'"';
                            break;
                    }
                }
            }
        }
        if (!empty($copy)) {
            $str .= 'class="copy-text" data-clipboard-text="'.$copy.'" ';
        }
        return $str;
    }

    /**
     * 解析地址
     * @param $data
     * @return string
     */
    public function url($data)
    {
        $str = '';
        if (!empty($this->url)) {
            if (!strstr('http', $this->url)) {
                $url = url($this->url);
                $str = 'href="'.$url.'"';
            }
            $urlParm = [];
            if (!empty($this->urlParm)) {
                foreach ($this->urlParm as $item) {
                    $tmp = explode(' ', $item);
                    if (count($tmp) > 1) {
                        if ('as' == $tmp[1]) {
                            $urlParm[$tmp[2]] = $data[$tmp[0]];
                        } elseif ('=' == $tmp[1]) {
                            $urlParm[$tmp[0]] = $tmp[2];
                        }
                    } else {
                        $urlParm[$item] = $data[$item] ??'';
                    }
                }
            }
            if (!empty($urlParm)) {
                $str .= ' data-params=\''.json_encode($urlParm).'\'';
            }
        }
        return $str;
    }
}
