@extends('layouts.app')

@section('title')
    Watte
@endsection

@section('content')

    <start-form liff="{{ $liff }}" deploy-url="{{$deploy_url}}"></start-form>

@endsection