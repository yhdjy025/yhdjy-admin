<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2019/1/4
 * Time: 10:50
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Model\UserRoleModel;
use App\Service\User\RoleService;
use App\Service\User\UserService;
use App\Tools\Form;
use App\Tools\Table;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $title = '用户管理';
        $email = request('email', '');
        $name = request('name', '');
        $userModel = $this->userService->getUserModel();
        if (!empty($email)) {
            $userModel->searchByEmail($email);
        }
        if (!empty($name)) {
            $userModel->searchByName($name);
        }
        $roles = (new UserRoleModel())->select()->items()->pluck('name', 'id')->all();
        $dataList = $userModel->select(config('system.per_page'))->items()->transform(function ($user)use ($roles)  {
            $user['role'] = $roles[$user['role_id']] ?? '-';
            return $user;
        });
        $navs = [
            'btns'   => [
                ['name' => 'del', 'url' => 'user/user/delete']
            ],
            'search' => [
                ['name' => 'name', 'title' => '用户名', 'type' => 'text'],
                ['name' => 'email', 'title' => '邮箱', 'type' => 'text']
            ]
        ];
        $table = (new Table())
            ->addCheckboxColumn('id')
            ->addtextColumn('name', '用户名')
            ->addtextColumn('email', '邮箱')
            ->addtextColumn('role', '角色')
            ->addtextColumn('created_at', '添加时间')
            ->addopColumn([
                ['field' => 'edit', 'title' => '编辑', 'url' => 'user/user/edit'],
                ['field' => 'delete', 'title' => '删除', 'url' => 'user/user/delete']
            ])
            ->setData($dataList);
        return view('user.user.index', compact('title', 'table', 'navs'));
    }

    /**
     * delete user
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $id = request('key', '');
        $this->userService->delEmailById($id);
        return $this->success('删除成功！');
    }

    /**
     * 编辑用户
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit()
    {
        $id = request('key');
        $user = $this->userService->getUserModel()->getById($id)->first();
        $roles = (new RoleService())
            ->getRoleModel()
            ->getByEnable()
            ->items()
            ->pluck('name', 'id')
            ->all();
        $form = (new Form())
            ->setAction('user/user/save')
            ->addInputHidden('id')
            ->addSelect('role_id', $roles, '用户角色')
            ->addInputRadio('is_lock', ['0' => '正常', '1' => '锁定'], '锁定状态')
            ->setData($user);
        return $this->success('success', view('user.user.edit',
            compact('form'))->render());
    }

    /**
     * 保存用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function save()
    {
        $data = request()->all();
        $uid = $data['id'];
        $parms = [
            'is_lock' => $data['is_lock'] ?? 0,
            'role_id' => $data['role_id'] ?? 0
        ];
        $this->userService->editUser($uid, $parms);
        return $this->success('保存成功！');
    }
}
