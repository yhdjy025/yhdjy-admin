@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ol class="breadcrumb">
                            <li><a href="#">首页</a></li>
                            <li><a href="#">{{ $title }}</a></li>
                        </ol>
                    </div>

                    <div class="panel-body" style="overflow: auto;">
                        <div class="list-group">
                            @foreach($nodes as $node)
                                <div class="list-group-item row">
                                    <div class="col-xs-7">
                                        {!!$node['spl']!!}{{$node['node']}}
                                        <div style="width: 200px;display: inline-block;margin-left: 10px;">
                                            <input class='title-input form-control input-sm' name="title.{{$node['type']}}.{{$node['node']}}" value="{{$node['title']}}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <label>@if($node['type'] == 1) web @elseif($node['type'] == 2)
                                                api @endif</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            @if(substr_count($node['node'],'/')>=1 && $node['type'] > 0)
                                                @if(!empty($node['is_auth']))
                                                    <input name="is_auth.{{$node['type']}}.{{$node['node']}}"
                                                           checked="checked" class="check-box" type="checkbox"
                                                           value="1"/>
                                                @else
                                                    <input name="is_auth.{{$node['type']}}.{{$node['node']}}"
                                                           class="check-box" type="checkbox" value="1"/>
                                                @endif
                                                加入权限控制
                                            @endif
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            @if(substr_count($node['node'],'/')>=1 && $node['type'] > 0)
                                                @if(!empty($node['is_log']))
                                                    <input name="is_log.{{$node['type']}}.{{$node['node']}}"
                                                           checked="checked" class="check-box" type="checkbox"
                                                           value="1"/>
                                                @else
                                                    <input name="is_log.{{$node['type']}}.{{$node['node']}}"
                                                           class="check-box" type="checkbox" value="1"/>
                                                @endif
                                                记录操作日志
                                            @endif
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            @if(substr_count($node['node'],'/')>=1 && $node['type'] == 1)
                                                @if(!empty($node['is_menu']))
                                                    <input name="is_menu.{{$node['type']}}.{{$node['node']}}"
                                                           checked="checked" class="check-box" type="checkbox"
                                                           value="1"/>
                                                @else
                                                    <input name="is_menu.{{$node['type']}}.{{$node['node']}}"
                                                           class="check-box" type="checkbox" value="1"/>
                                                @endif
                                                可设为菜单
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('input.title-input').on('blur', function () {
                var data = {
                    name: this.name,
                    value: this.value
                };
                saveNodef(data);
            });
            $('input.check-box').on('click', function () {
                var data = {
                    name: this.name,
                    value: this.checked ? 1 : 0
                };
                saveNodef(data);
            });
        });

        function saveNodef(data) {
            $.post("{{ url('system/node/save') }}", data, function (ret) {
                if (ret.code == 200) {
                    layer.msg(ret.message, {icon: 6, shade: 0.3, time:300})
                } else {
                    layer.msg(ret.message, {icon: 5, shade: 0.3})
                }
            })
        }
    </script>
@endsection
