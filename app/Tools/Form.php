<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-06
 * Time: 20:31
 */

namespace App\Tools;


use Illuminate\Support\Collection;

class Form
{
    /**
     * @var Collection
     */
    public $items;
    public $action;
    public $method = 'post';
    public $data;
    public $minHeight = '0px';

    public function __construct()
    {
        $this->items = new Collection();
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return Form
     */
    public function setAction($action)
    {
        if (!strstr('http', $action)) {
            $action = url($action);
        }
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function addInputHidden($name)
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->tag = 'input';
        $formItem->type = 'hidden';
        $this->items->push($formItem);
        return $this;
    }

    /**
     * add input text
     * @param        $name
     * @param string $title
     * @param string $default
     * @param bool $disabled
     * @return $this
     */
    public function addInputText($name, $title = '', $default = '', $disabled = false)
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'input';
        $formItem->type = 'text';
        $formItem->default = $default;
        $formItem->disabled = $disabled;
        $this->items->push($formItem);
        return $this;
    }

    /**
     * add input password
     * @param        $name
     * @param string $title
     * @return $this
     */
    public function addInputPassword($name, $title = '')
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'input';
        $formItem->type = 'password';
        $this->items->push($formItem);
        return $this;
    }

    /**
     * add input radio
     * @param        $name
     * @param        $options
     * @param string $title
     * @return $this
     */
    public function addInputRadio($name, $options, $title = '')
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'input';
        $formItem->type = 'radio';
        $formItem->options = $options;
        $this->items->push($formItem);
        return $this;
    }

    /**
     * add input checkbox
     * @param        $name
     * @param        $options
     * @param string $title
     * @return $this
     */
    public function addInputCheckbox($name, $options, $title = '')
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'input';
        $formItem->type = 'checkbox';
        $formItem->options = $options;
        $this->items->push($formItem);
        return $this;
    }

    /**
     * add select
     * @param        $name
     * @param        $options
     * @param string $title
     * @param bool $search
     * @return $this
     */
    public function addSelect($name, $options, $title = '', $search = false)
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'select';
        $formItem->options = $options;
        $formItem->search = $search;
        $this->items->push($formItem);
        return $this;
    }

    /**
     *  add date picker
     * @param $name
     * @param $title
     * @return $this
     */
    public function addDatePicker($name, $title)
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'datePicker';
        $this->items->push($formItem);
        return $this;
    }

    /**
     * 图pain上传
     * @param $name
     * @param string $title
     * @return $this
     */
    public function addImgUpload($name, $title = '文件上传')
    {
        $formItem = new FormItem();
        $formItem->name= $name;
        $formItem->title= $title;
        $formItem->tag = 'upload';
        $formItem->type = 'image';
        $this->items->push($formItem);
        return $this;
    }

    /**
     * set bottom
     * @param $value
     * @return $this
     */
    public function setMinHeight($value)
    {
        $this->minHeight = $value;
        return $this;
    }

    /**
     * set data
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}