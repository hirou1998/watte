@extends('layouts.app')

@section('title')
    一覧確認
@endsection

@section('content')
    <amount-lists :amounts="{{$amount_lists}}" :each="{{$each_calc_amount}}" liff="{{$liff}}" deploy-url="{{$deploy_url}}" :event="{{$event}}"></amount-lists>
@endsection