@extends('layouts.app')

@section('title')
    参加者確認
@endsection

@section('content')
    <participants liff="{{$liff}}" :event="{{$event}}" :participants="{{$participants}}" deploy-url="{{$deploy_url}}"></participants>
@endsection