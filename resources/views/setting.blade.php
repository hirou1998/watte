@extends('layouts.app')

@section('title')
    設定
@endsection

@section('content')
    <setting liff="{{$liff}}" :event="{{$event}}" deploy-url="{{$deploy_url}}"></setting>
@endsection