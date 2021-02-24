@extends('layouts.app')

@section('title')
    支払いリクエスト
@endsection

@section('content')
    <request
        :transaction="{{$transaction}}"
        action-type="{{$action_type}}"
        liff="{{$liff}}" 
        deploy-url="{{$deploy_url}}"
        :participants="{{$participants}}"
    ></request>
@endsection