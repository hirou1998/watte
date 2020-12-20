@extends('layouts.app')

@section('title')
    一覧確認
@endsection

@section('content')
    <h1 class="amount-show-title txt-big">{{$event->event_name}}</h1>
    <amount-lists :amounts="{{$amount_lists}}" :each="{{$each_calc_amount}}"></amount-lists>
@endsection