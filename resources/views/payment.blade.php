@extends('layouts.app')

@section('title')
    支払いの承認
@endsection

@section('content')
    <payment
        :transaction="{{$transaction}}"
        action-type="{{$action_type}}"
        liff="{{$liff}}" 
        deploy-url="{{$deploy_url}}"
        :participants="{{$participants}}"
    ></payment>
@endsection