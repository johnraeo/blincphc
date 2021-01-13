@extends('layouts.app')

@section('content')
    <div id="app">
        <main>
            <navbar-component></navbar-component>
            <router-view></router-view>
        </main>

    </div>
@endsection