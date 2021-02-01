@extends('layouts.app')

@section('title')
    一覧確認
@endsection

@section('content')
    <amount-lists
        :participants="{{$participants}}"
        liff="{{$liff}}" 
        deploy-url="{{$deploy_url}}" 
        :event="{{$event}}"
    ></amount-lists>
@endsection