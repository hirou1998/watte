@extends('layouts.app')

@section('title')
    割り勘を追加
@endsection

@section('content')
    <add-amount :event="{{$event}}" liff="{{$liff}}" :participants="{{$participants}}" deploy-url="{{$deploy_url}}"></add-amount>
@endsection