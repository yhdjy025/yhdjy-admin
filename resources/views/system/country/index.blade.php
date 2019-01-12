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
                        @include('vendor.search')
                    </div>

                    <div class="panel-body" style="overflow: auto;">
                        @include('vendor.table', ['_table' => $table])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
