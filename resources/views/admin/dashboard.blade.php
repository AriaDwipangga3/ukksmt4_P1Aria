@extends('layouts.app')


@section('content')


    <h1>Selamat datang, {{ Auth::user()->name }} (Admin)</h1>

@endsection
