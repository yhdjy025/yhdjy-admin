<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2018-12-25
 * Time: 22:38
 */

namespace App\Service\System;


use App\Exceptions\AppException;
use App\Model\MenuModel;
use App\Service\BaseService;
use App\Service\Common\CommonUtils;
use App\Service\Common\PermissionsService;
use App\Service\Common\ToolsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MenuService extends BaseService
{
    public function getMenuModel()
    {
        return new MenuModel();
        return $this;
    }

    /**
     * 编辑菜单
     * @param $params
     * @return bool|int
     * @throws AppException
     */
    public function editMenu($params)
    {
        if (empty($params['title'])) {
            throw new AppException('菜单名称不能为空');
        }
        $data = [
            'title' => $params['title'],
            'pid' => $params['pid'],
            'url' => $params['url'] ?? '#',
            'status' => $params['status'] ?? 1,
            'sort' => $params['sort'],
            'target' => $params['target'] ?? ''
        ];
        if (empty($params['id'])) {
            $data['created_at'] = time();
        }
        \Cache::forget('able_menu');
        return $this->getMenuModel()->edit($data, $params['id'] ?? null);
    }

    /**
     * 删除菜单
     * @param $id
     * @return int|mixed
     */
    public function delMenuById($id)
    {
        \Cache::forget('able_menu');
        return $this->getMenuModel()->delById($id);
    }

    /**
     * 取得所有有效菜单
     * @return \Illuminate\Support\Collection|LengthAwarePaginator
     */
    public function getAbleMenus()
    {
        $menus = \Cache::rememberForever('able_menu', function () {
            return $this->getMenuModel()->getEnabled()->sortBy(['sort' => 'asc', 'id' => 'asc'])->items();
        });
        return $menus;
    }

    /**
     * 取得该用户的授权菜单
     * @param $gid
     * @return array|array
     */
    public function getAuthMenus($gid)
    {
        $datas = $this->getAbleMenus()->all();
        $perm = PermissionsService::getAllPermissions($gid);
        if (empty($perm['power']) && !isAdmin()) {
            return [];
        }
        if (!empty($perm['power']) && !isAdmin()) {
            $permissions = $perm['power'];
            $auth_nodes = $this->getNodesFromPermissions($permissions);
            $datas = array_filter($datas, function ($data) use ($auth_nodes) {
                if (CommonUtils::startsWith($data['url'], '/') && trim($data['url']) != '/') {
                    $data['url'] = substr($data['url'], 1);
                }
                if (in_array($data['url'], $auth_nodes) || $data['url'] == '#') {
                    return true;
                }

                return false;
            });
        }

        $menus = $this->_filterMenu(ToolsService::arr2tree($datas));
        return $menus;
    }

    /***
     *
     * @param $mTree
     * @param $url
     * @return multitype:\App\Services\System\Ambigous
     */
    public function getMenuPath($mTree, $url)
    {
        $menu = [];
        $url = url(CommonUtils::wipeBracket($url));

        $map = ToolsService::tree2Map($mTree);

        if (!empty($map) && !empty($url)) {
            foreach ($map as $item) {
                if ($item['url'] == $url) {
                    $menu = $item;
                    break;
                }
            }
        }

        if (empty($menu)) {
            return $menu;
        }

        $parents = $this->getParents($map, $menu);
        $parents = array_reverse($parents);
        $parents[] = $menu;

        return $parents;
    }

    /**
     * 获取父节点，并组成队列
     * @param $map
     * @param $menu
     * @return array
     */
    private function getParents($map, $menu)
    {
        $parents = [];
        if (empty($map) || empty($menu)) {
            return $parents;
        }

        if (!empty($menu['pid']) && array_key_exists($menu['pid'], $map)) {
            $sup = isset($map[$menu['pid']]) ? $map[$menu['pid']] : [];
            if (!empty($sup)) {
                $parents[] = $sup;
                CommonUtils::array_add($parents, $this->getParents($map, $sup));
            }
        }

        return $parents;
    }

    /**
     * 后台主菜单权限过滤
     * @param array $menus
     * @return array
     */
    private function _filterMenu($menus)
    {
        foreach ($menus as $key => &$menu) {
            if (!empty($menu['sub'])) {
                $menu['sub'] = $this->_filterMenu($menu['sub']);
            }
            if (!empty($menu['sub'])) {
                $menu['url'] = '#';
            } elseif (stripos($menu['url'], 'http') === 0) {
                continue;
            } elseif ($menu['url'] !== '#' && !empty(join('/', array_slice(explode('/', $menu['url']), 0, 3)))) {
                $menu['url'] = url($menu['url']);
            } else {
                unset($menus[$key]);
            }
        }
        return $menus;
    }

    /**
     * 获取其他菜单
     * @param $vo
     * @return array
     */
    public function getMenusExcept($vo)
    {
        // 上级菜单处理
        $_menus = $this->getAbleMenus();
        $_menus[] = ['title' => '顶级菜单', 'id' => '0', 'pid' => '-1'];
        $menus = ToolsService::arr2table($_menus);
        foreach ($menus as $key => &$menu) {
            if (substr_count($menu['path'], '-') > 3) {
                unset($menus[$key]);
                continue;
            }
            if (isset($vo['pid'])) {
                $current_path = "-{$vo['pid']}-{$vo['id']}";
                if ($vo['pid'] !== '' && (stripos("{$menu['path']}-", "{$current_path}-") !== false || $menu['path'] === $current_path)) {
                    unset($menus[$key]);
                }
            }
        }

        return $menus;
    }

    /**
     * 所有permision的节点信息
     * @param $permissions
     * @return multitype:
     */
    private function getNodesFromPermissions($permissions)
    {
        $nodes = [];
        if (empty($permissions)) {
            return $nodes;
        }

        $nodes = explode(',', $permissions);

        return array_unique($nodes);
    }
}
