<?php

namespace App\Service\System;

use App\Service\BaseService;
use Illuminate\Support\Facades\DB;
use App\Service\Common\RouteService;
use App\Service\Common\CommonUtils;

/**
 * 系统权限节点读取器
 * Class NodeService
 * @package extend
 * @author hky
 * @date 2018/04/09 11:28
 */
class NodeService extends BaseService
{
    private static $instance;

    /**
     * 返回唯一实例
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new NodeService();
        }
        return self::$instance;
    }

    /**
     * 根据node名字返回该条数据
     * @param $node
     * @return \Illuminate\Support\Collection|void
     */
    public function getNode($node)
    {
        return $this->firstWhere('node', [
            ['node', '=',  $node]
        ]);
    }

    /**
     * 获取其他的节点
     * @return array
     */
    public function getNodesIsMenu()
    {
        // 读取系统功能节点
        $nodes = $this->getAll();
        foreach ($nodes as $key => $_vo) {
            if (empty($_vo['is_menu'])) {
                unset($nodes[$key]);
            }
        }

        return $nodes;
    }

    /**
     * 获取系统代码节点
     * @return array
     */
    public function getAll()
    {
        $alias = [];
        $vos = DB::table('node')->get()->toArray();
        foreach ($vos as $vo) {
            $alias["{$vo['node']}"] = $vo;
        }
        $nodes = [];
        $routes = collect(RouteService::getRoutes());
        foreach (config('pretty-routes.hide_matching') as $regex) {
            $routes = $routes->filter(function ($value, $key) use ($regex) {
                return !preg_match($regex, $value['url']);
            });
        }

        foreach ($routes as $route) {
            $url = $route['url'];
            $real_url = CommonUtils::wipeBracket($url);
            $segments = explode('/', $url);
            $path = '';

            // 根目录
            if (empty($real_url) || trim($real_url) == '/') {
                $path = '/';
                $znode = [];
                if (!isset($nodes[$path])) {
                    $znode[$path] = isset($alias[$path]) ? $alias[$path] : ['node' => $path, 'title' => '', 'type' => 0, 'is_menu' => 0, 'is_auth' => 1, 'is_log' => 0];
                    $nodes = CommonUtils::array_push($znode, $nodes);
                }
            } else if (in_array($real_url, config('pretty-routes.homepage_matching'))) {
                // 首页的节点，挂到首页二层节点下
                $path = $real_url;
                $nodes[$path] = array_merge(isset($alias[$path]) ? $alias[$path] : ['node' => $path, 'title' => '', 'type' => 0, 'is_menu' => 0, 'is_auth' => 1, 'is_log' => 0], ['pnode' => $p_path]);
                $nodes[$path]['pnode'] = 'index';
            } else {
                foreach ($segments as $segment) {
                    if (empty($segment) || (CommonUtils::startsWith($segment, '{') && CommonUtils::endsWith($segment, '}'))) {
                        break;
                    }

                    $p_path = $path;
                    $path = empty($path) ? $segment : "{$path}/{$segment}";
                    $nodes[$path] = array_merge(isset($alias[$path]) ? $alias[$path] : ['node' => $path, 'title' => '', 'type' => 0, 'is_menu' => 0, 'is_auth' => 1, 'is_log' => 0], ['pnode' => $p_path]);
                }
            }

            // 0: 未标识类别；1：web调用；2：api接口
            if (CommonUtils::startsWith($route['middleware'], 'web')) {
                $nodes[$path]['type'] = 1;
            } else if (CommonUtils::startsWith($route['middleware'], 'api')) {
                $nodes[$path]['type'] = 2;
            }
        }

        return $nodes;
    }

    /**
     * 获取节点列表
     * @param string $path 路径
     * @param array $nodes 额外数据
     * @return array
     */
    public function getNodeTree($path, $nodes = [])
    {
        foreach (self::_getFilePaths($path) as $vo) {
            if (!preg_match('|/(\w+)/controller/(\w+)|', str_replace(DS, '/', $vo), $matches) || count($matches) !== 3) {
                continue;
            }
            $className = config('app_namespace') . str_replace('/', '\\', $matches[0]);
            if (!class_exists($className)) {
                continue;
            }
            foreach (get_class_methods($className) as $actionName) {
                if ($actionName[0] !== '_') {
                    $nodes[] = strtolower("{$matches[1]}/{$matches[2]}/{$actionName}");
                }
            }
        }
        return $nodes;
    }

    /**
     * 获取所有PHP文件
     * @param string $path 目录
     * @param array $data 额外数据
     * @param string $ext 文件后缀
     * @return array
     */
    private function _getFilePaths($path, $data = [], $ext = 'php')
    {
        foreach (scandir($path) as $dir) {
            if ($dir[0] === '.') {
                continue;
            }
            if (($tmp = realpath($path . DS . $dir)) && (is_dir($tmp) || pathinfo($tmp, PATHINFO_EXTENSION) === $ext)) {
                is_dir($tmp) ? $data = array_merge($data, self::_getFilePaths($tmp)) : $data[] = $tmp;
            }
        }
        return $data;
    }

}
