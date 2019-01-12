@extends('layouts.app')

@section('content')
    <style>
        ul.ztree li span.button.switch{margin-right:5px}
        ul.ztree ul ul li{display:inline-block;white-space:normal}
        ul.ztree>li>ul>li{padding:5px}
        ul.ztree>li{background: #dae6f0}
        ul.ztree>li:nth-child(even)>ul>li:nth-child(even){background: #eef5fa}
        ul.ztree>li:nth-child(even)>ul>li:nth-child(odd){background: #f6fbff}
        ul.ztree>li:nth-child(odd)>ul>li:nth-child(even){background: #eef5fa}
        ul.ztree>li:nth-child(odd)>ul>li:nth-child(odd){background: #f6fbff}
        ul.ztree>li>ul{margin-top:12px}
        ul.ztree>li{padding: 15px 25px 15px 15px;}
        ul.ztree li{white-space:normal!important}
        ul.ztree>li>a>span{font-size:15px;font-weight:700}
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ol class="breadcrumb">
                            <li><a href="#">首页</a></li>
                            <li><a href="#">{{ $title }}</a></li>
                        </ol>
                        @include('vendor.search')
                    </div>

                    <div class="panel-body" style="overflow: auto;">
                        <ul id="zTree" class="ztree">
                            <li style="height:100px;"></li>
                        </ul>

                        <div class="hr-line-dashed"></div>

                        <div class="se-bt-r">
                            <a class="btn btn-primary btn-sm" data-submit-role href="javascript:void(0)" data-url="{{url('user/role/endow')}}/{{$id}}">保存数据</a>
                            <a class="btn btn-danger btn-sm" href="javascript:void(0)" onclick="window.close()">取消编辑</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugn/zTree_v3/css/zTreeStyle/zTreeStyle.css') }}">
@endsection

@section('js')
    <script src="{{ asset('plugn/zTree_v3/js/jquery.ztree.all.min.js') }}"></script>
    <script>
        ;(function(){
            var id = "{{ $id }}";
            $.NodeTree = function () {
                function showTree() {
                    this.data = {};
                    this.ztree = null;
                    this.setting = {
                        view: {showLine: false, showIcon: false, dblClickExpand: false},
                        check: {enable: true, nocheck: false, chkboxType: {"Y": "ps", "N": "ps"}},
                        callback: {
                            beforeClick: function (treeId, treeNode) {
                                if (treeNode.children.length < 1) {
                                    window.roleForm.ztree.checkNode(treeNode, !treeNode.checked, null, true);
                                } else {
                                    window.roleForm.ztree.expandNode(treeNode);
                                }
                                return false;
                            }}};
                    this.listen();
                }
                showTree.prototype = {
                    constructor: showTree,
                    listen: function () {
                        this.getData(this);
                    },
                    getData: function (self) {
                        //$.msg.loading();
                        jQuery.post("{{url('user/role/node')}}", {id: id, op: 'getNode'}, function (ret) {
                            //$.msg.close();
                            function renderChildren(data, level) {
                                var childrenData = [];
                                for (var i in data) {
                                    var children = {};
                                    children.open = true;
                                    children.node = data[i]['node'];
                                    children.name = data[i]['title'] || data[i]['node'];
                                    children.checked = data[i]['checked'] || false;
                                    children.children = renderChildren(data[i]['_sub_'], level + 1);
                                    childrenData.push(children);
                                }
                                return childrenData;
                            }
                            self.data = renderChildren(ret.data, 1);
                            self.showTree();
                        }, 'JSON');
                    },
                    showTree: function () {
                        this.ztree = jQuery.fn.zTree.init(jQuery("#zTree"), this.setting, this.data);
                        while (true) {
                            var reNodes = this.ztree.getNodesByFilter(function (node) {
                                return  (!node.node && node.children.length < 1);
                            });
                            if (reNodes.length < 1) {
                                break;
                            }
                            for (var i in reNodes) {
                                this.ztree.removeNode(reNodes[i]);
                            }
                        }
                    },
                    submit: function () {
                        var nodes = [];
                        var data = this.ztree.getCheckedNodes(true);
                        for (var i in data) {
                            (data[i].node) && nodes.push(data[i].node);
                        }
                        $.post("{{ url('user/role/node') }}", {
                            id: id,
                            op: 'save',
                            nodes: nodes
                        }, function (ret) {
                            layer.msg(ret.message, {icon: ret.code == 200 ? 6 : 5, shade:0.3});
                        })
                    }};
                window.roleForm = new showTree();
                $('[data-submit-role]').on('click', function () {
                    window.roleForm.submit();
                });
            };
        })(jQuery);

        var nTree = new $.NodeTree();
    </script>
@endsection
