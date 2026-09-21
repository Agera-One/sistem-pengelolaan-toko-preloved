@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Halo, {{ auth()->user()->name }}!</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
