@extends('layouts.app')

@section('title')
    イベント情報編集
@endsection

@section('content')
    <event-edit
        liff="{{ $liff }}" 
        deploy-url="{{$deploy_url}}"
        event-id="{{$event_id}}"
    ></event-edit>
@endsection