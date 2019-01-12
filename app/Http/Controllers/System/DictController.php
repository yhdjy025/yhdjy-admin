<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-10-14
 * Time: 19:37
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Controller;
use App\Service\System\DictService;
use App\Tools\Form;
use App\Tools\Table;
use PhpParser\Node\Expr\Cast\Object_;

class DictController extends Controller
{
    private $dictService;

    public function __construct(DictService $dictService)
    {
        $this->dictService = $dictService;
    }

    public function index()
    {
        $title = '数据字典';
        $dataList = $this->dictService
            ->getDictModel()
            ->select(config('system.per_page'))
            ->items();
        $navs = [
            'btns' => [
                ['name' => 'add', 'url' => 'system/dict/add'],
                ['name' => 'del', 'url' => 'system/dict/delete']
            ]
        ];
        $table = (new Table())
            ->addCheckboxColumn('id')
            ->addtextColumn('name', '字典名称')
            ->addtextColumn('title', '字典标题')
            ->addtextColumn('created_at', '添加时间', 'DATE')
            ->addopColumn([
                ['field' => 'edit', 'title' => '编辑', 'url' => 'system/dict/edit'],
                ['field' => 'link', 'title' => '设置值', 'url' => 'system/dict/dictvalue'],
                ['field' => 'delete', 'title' => '删除', 'url' => 'system/dict/delete']
            ])
            ->setData($dataList);
        return view('system.dict.index', compact('title', 'table', 'navs'));
    }

    /**
     * add dict
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add()
    {
        $types = [];
        $form = (new Form())
            ->setAction('system/dict/save')
            ->addInputHidden('id')
            ->addInputText('name', '字典名称')
            ->addInputText('title', '字典标题');
        return $this->success('success', view('system.dict.edit',
            compact('form'))->render());
    }

    /**
     * edit dict
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit()
    {
        $id = request('key', '');
        $types = [];
        $data = $this->dictService
            ->getDictModel()
            ->getById($id)
            ->first();
        $form = (new Form())
            ->setAction('system/dict/save')
            ->addInputHidden('id')
            ->addInputText('name', '字典名称')
            ->addInputText('title', '字典标题')
            ->setData($data);
        return $this->success('success', view('system.dict.edit',
            compact('form'))->render());
    }

    /**
     * save add or dict
     * @return \Illuminate\Http\JsonResponse
     */
    public function save()
    {
        $params = request()->all();
        $this->dictService->editDict($params);
        return $this->success('保存成功！');
    }

    /**
     * delete dict
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete()
    {
        $id = request('key', '');
        $this->dictService->delDictById($id);
        return $this->success('删除成功！');
    }

    /**
     * 数据字典键值
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dictValue()
    {
        $title = '数据字典';
        $id = request('key', '');
        $dataList = $this->dictService->getDictValueModel()->getByDictId($id)->items();
        $navs = [
            'btns' => [
                ['name' => 'add', 'url' => url('system/dict/addvalue').'?id='.$id],
                ['name' => 'del', 'url' => 'system/dict/deleteValue']
            ]
        ];
        $table = (new Table())
            ->addCheckboxColumn('id')
            ->addtextColumn('name', '字典名称')
            ->addtextColumn('key', '字典键')
            ->addtextColumn('value', '字典值')
            ->addtextColumn('created_at', '添加时间', 'DATE')
            ->addopColumn([
                ['field' => 'edit', 'title' => '编辑', 'url' => 'system/dict/editValue'],
                ['field' => 'delete', 'title' => '删除', 'url' => 'system/dict/deleteValue']
            ])
            ->setData($dataList);
        return view('system.dict.dictvalue', compact('title', 'table', 'navs'));
    }

    /**
     * add dict value
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function addValue()
    {
        $id = request('id');
        $dict = $this->dictService->getDictModel()->getById($id)->select()->first();
        $data['name'] = $dict['name'];
        $form = (new Form())
            ->setAction('system/dict/savevalue')
            ->addInputHidden('id')
            ->addInputText('name', '字典名称')
            ->addInputText('key', '字典键')
            ->addInputText('value', '字典值')
            ->setData($data);
        return $this->success('success', view('system.dict.editvalue',
            compact('form'))->render());
    }

    /**
     * edit dict value
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function editValue()
    {
        $id = request('key', '');
        $data = $this->dictService->getDictValueModel()->getById($id)->first();
        $form = (new Form())
            ->setAction('system/dict/savevalue')
            ->addInputHidden('id')
            ->addInputText('name', '字典名称')
            ->addInputText('key', '字典键')
            ->addInputText('value', '字典值')
            ->setData($data);
        return $this->success('success', view('system.dict.editvalue',
            compact('form'))->render());
    }

    /**
     * save add or dict value
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveValue()
    {
        $params = request()->all();
        $this->dictService->editValue($params);
        return $this->success('保存成功！');
    }

    /**
     * delete dict value
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function deleteValue()
    {
        $id = request('key', '');
        $this->dictService->delValueById($id);
        return $this->success('删除成功！');
    }
}
