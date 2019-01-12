@extends('layouts.public')

@section('content')
    <div class="container">
            <p class="alert text-center">{{ $message }}</p>
            <p class="text-center"><a class="btn btn-default btn-sm" href="{{ url('/') }}" role="button">返回首页</a></p>
    </div>
@endsection
