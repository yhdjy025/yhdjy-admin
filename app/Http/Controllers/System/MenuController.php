<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-12-25
 * Time: 22:38
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Controller;
use App\Service\System\MenuService;
use App\Tools\Form;
use App\Tools\Table;

class MenuController extends Controller
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        $title = '菜单列表';
        $pid = request('pid', 0);
        $menuModel = $this->menuService->getMenuModel();
        $menuModel->getByPid($pid);
        $dataList = $menuModel->select(config('system.per_page'))->items();
        $pMenu = [];
        if (!empty($pid)) {
            $pMenu = $this->menuService->getMenuModel()->getById($pid)->first();
        }
        $navs = [
            'btns' => [
                ['name' => 'add', 'url' => url('system/menu/add').'?pid='.$pid],
            ]
        ];
        $table = (new Table())
            ->addLinkColumn('title', '菜单名称', 'system/menu/index', ['id as pid'])
            ->addtextColumn('url', '地址')
            ->addtextColumn('created_at', '创建时间', 'DATE')
            ->addopColumn([
                ['field' => 'edit', 'title' => '编辑', 'url' => 'system/menu/edit'],
                ['field' => 'delete', 'title' => '删除', 'url' => 'system/menu/delete']
            ])
            ->setData($dataList);
        return view('system.menu.index', compact('title', 'table', 'navs', 'pMenu'));
    }

    /**
     * add dict
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add()
    {
        $pid = request('pid', 0);
        $pTitle = '顶级菜单';
        if ($pid) {
            $menu = $this->menuService->getMenuModel()->getById($pid)->first();
            $pTitle = $menu['title'];
        }
        $data = [
            'pTitle' => $pTitle,
            'pid' => $pid,
            'sort' => 0
        ];
        $form = (new Form())
            ->setAction('system/menu/save')
            ->addInputHidden('id')
            ->addInputHidden('pid')
            ->addInputText('pTitle', '上级菜单', '', true)
            ->addInputText('title', '菜单名称')
            ->addInputText('url', '菜单地址')
            ->addInputText('target', '打开方式')
            ->addInputText('sort', '排序')
            ->addInputRadio('status', ['1' => '启用', '0' => '禁用'], '状态')
            ->setData($data);
        return $this->success('success', view('system.menu.edit',
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
        $data = $this->menuService
            ->getMenuModel()
            ->getById($id)
            ->first();
        $form = (new Form())
            ->setAction('system/menu/save')
            ->addInputHidden('id')
            ->addInputHidden('pid')
            ->addInputText('title', '菜单名称')
            ->addInputText('url', '菜单地址')
            ->addInputText('target', '打开方式')
            ->addInputText('sort', '排序')
            ->addInputRadio('status', ['1' => '启用', '0' => '禁用'], '状态')
            ->setData($data);
        return $this->success('success', view('system.menu.edit',
            compact('form'))->render());
    }

    /**
     * save add or dict
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function save()
    {
        $params = request()->all();
        $this->menuService->editMenu($params);
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
        $this->menuService->delMenuById($id);
        return $this->success('删除成功！');
    }
}
