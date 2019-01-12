<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/12/29
 * Time: 15:18
 */

namespace App\Http\Controllers\System;


use App\Http\Controllers\Controller;
use App\Service\Common\ToolsService;
use App\Service\System\NodeService;

class NodeController extends Controller
{
    protected $nodeService;

    public function __construct() {
        $this->nodeService = new NodeService();
    }

    /**
     * 显示节点列表
     */
    public function index() {
        $title = '系统节点管理';
        $nodes = ToolsService::arr2table($this->nodeService->getAll(), 'node', 'pnode');
        return view('system.node.index', compact('title', 'alert', 'nodes'));
    }

    /**
     * 保存节点变更
     */
    public function save() {
        $input=request()->all();

        if (isset($input['name']) && isset($input['value'])) {
            $nameattr = explode('.', $input['name']);
            $field = array_shift($nameattr);
            $type = array_shift($nameattr);
            $data = ['node' => join(',', $nameattr), $field => $input['value'], 'type' => $type, 'updated_at' => date('Y-m-d H:i:s')];
            $rslt = $this->nodeService->updateOrCreate('node', ['node'=>$data['node']], $data);
            return $this->success("参数保存成功!");
        }

        return $this->error("参数错误!");
    }
}
