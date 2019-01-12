<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2019/1/4
 * Time: 14:16
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Service\Common\ToolsService;
use App\Service\System\NodeService;
use App\Service\User\RoleService;
use App\Tools\Form;
use App\Tools\Table;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * email list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = '角色管理';
        $roleModel = $this->roleService->getRoleModel();
        $dataList = $roleModel->select(config('system.per_page'))->items();
        $navs = [
            'btns' => [
                ['name' => 'add', 'url' => 'user/role/add'],
                ['name' => 'del', 'url' => 'user/role/delete']
            ]
        ];
        $table = (new Table())
            ->addCheckboxColumn('id')
            ->addtextColumn('name', '角色名称')
            ->addtextColumn('desc', '描述')
            ->addtextColumn('user_num', '用户数')
            ->addtextColumn('status', '状态', 'DICT.STATUS')
            ->addtextColumn('created_at', '添加时间', 'DATE')
            ->addopColumn([
                ['field' => 'link', 'title' => '授权', 'url' => 'user/role/node', 'urlParm' => ['id'], 'attrs' => ['target' => '_blank']],
                ['field' => 'edit', 'title' => '编辑', 'url' => 'user/role/edit'],
                ['field' => 'delete', 'title' => '删除', 'url' => 'user/role/delete']
            ])
            ->setData($dataList);
        return view('user.role.index', compact('title', 'table', 'navs'));
    }

    /**
     * add email
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function add()
    {
        $form = (new Form())
            ->setAction('user/role/save')
            ->addInputHidden('id')
            ->addInputText('name', '角色名称')
            ->addInputText('desc', '描述')
            ->addInputRadio('status', ['1' => '启用', '0' => '禁用'], '状态');
        return $this->success('success', view('user.role.edit',
            compact('form'))->render());
    }

    /**
     * edit email
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit()
    {
        $id = request('key', '');
        $data = $this->roleService
            ->getEmailModel()
            ->getById($id)
            ->first();
        $form = (new Form())
            ->setAction('user/role/save')
            ->addInputHidden('id')
            ->addInputText('name', '角色名称')
            ->addInputText('desc', '描述')
            ->addInputRadio('status', ['1' => '启用', '0' => '禁用'], '状态')
            ->setData($data);
        return $this->success('success', view('user.role.edit',
            compact('form'))->render());
    }

    /**
     * save add or edit
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function save()
    {
        $params = request()->all();
        $this->roleService->editRole($params);
        return $this->success('保存成功！');
    }

    /**
     * delete email
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function delete()
    {
        $id = request('key', '');
        $this->roleService->delEmailById($id);
        return $this->success('删除成功！');
    }

    public function node()
    {
        $id = request('id', '');
        $title = '用户角色授权 ';
        $op = request('op');
        if ($op == 'getNode') {
            $role = $this->roleService->getRoleModel()->getById($id)->first();
            $nodes = (new NodeService())->getAll();
            $checked = explode(',', $role['permissions']);
            foreach ($nodes as $key => &$node) {
                $node['checked'] = in_array($node['node'], $checked);
                if (empty($node['is_auth']) && substr_count($node['node'], '/') > 1) {
                    unset($nodes[$key]);
                }
            }
            $nodes = ToolsService::arr2tree($nodes, 'node', 'pnode', '_sub_');
            $nodes = $this->_filterNodes($nodes);
            return $this->success('success', $nodes);
        } else if ($op == 'save') {
            $post = request()->all();
            $nodes = request('nodes', []);
            $ret = $this->roleService->setPermissions($id, $nodes);
            if($ret){
                return $this->success('角色授权成功！', '');
            }else{
                return $this->success('角色授权失败！', '');
            }
        }
        return view('user.role.node', compact('title', 'id'));
    }

    /**
     * 节点数据拼装
     * @param array $nodes
     * @param int $level
     * @return array
     */
    protected function _filterNodes($nodes, $level = 1) {
        foreach ($nodes as $key => &$node) {
            if (!empty($node['_sub_']) && is_array($node['_sub_'])) {
                $node['_sub_'] = $this->_filterNodes($node['_sub_'], $level + 1);
            } elseif ($node['is_auth'] != 1) {
                // 不需要权限管理，不展示
                unset($nodes[$key]);
            }
        }
        return $nodes;
    }
}
