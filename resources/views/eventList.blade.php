@extends('layouts.app')

@section('title')
    イベント一覧
@endsection

@section('content')
    <event-list
        liff="{{ $liff }}" 
        deploy-url="{{$deploy_url}}"
    ></event-list>
@endsection