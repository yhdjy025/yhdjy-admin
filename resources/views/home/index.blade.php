@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row task-count">
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-heading">
                        <strong>用户统计</strong>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <a class="list-group-item" href="javascript:;">
                                <span class="count">100</span>
                                <p>总量</p>
                            </a>
                            <a class="list-group-item">
                                <span class="count">0</span>
                                <p>推荐</p>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-heading">
                        <strong>本月任务统计</strong>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <a class="list-group-item" href="javascript:;">
                                <span class="count">100</span>
                                <p>总数</p>
                            </a>
                            <a class="list-group-item" href="javascript:;">
                                <span class="count">100</span>
                                <p>推荐数量</p>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-heading">
                        <strong>待处理任务统计</strong>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <a class="list-group-item" href="javascript:;">
                                <span class="count">100</span>
                                <p>待处理</p>
                            </a>
                            <a class="list-group-item" href="javascript:;">
                                <span class="count">50</span>
                                <p>紧急处理</p>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-heading">
                        <strong>任务完成统计</strong>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="count">50</span>
                                <p>本月数量</p>
                            </li>
                            <li class="list-group-item">
                                <span class="count">100</span>
                                <p>总数</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
