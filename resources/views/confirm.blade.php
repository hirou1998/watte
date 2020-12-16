@extends('layouts.app');

@section('title')
    参加確認
@endsection

@section('content')
    <confirm :event="{{$item}}" join="{{$join}}" liff="{{$liff}}"></confirm>
@endsection