@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Support / FAQ
    </h2>
@endsection

@section('content')
    @include('user.support.index')
@endsection
