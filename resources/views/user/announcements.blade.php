@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Announcements
    </h2>
@endsection

@section('content')
    @include('user.announcements.index')
@endsection
