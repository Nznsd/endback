@extends('layouts.error')

@section('title', '404 - Not Found')

@section('content')
            <p class="header">O boy! the page you requested for does not exist</p>
            <p class="text">We advice you go back to safety, we mean <a href="javascript:window.history.go(-1)">the previous page</a> 😇 </p>
            <section class="num">
                <span>404</span><img class="error-404" src="{{ asset('assets/img/errors/compass.png') }}" alt="compass" srcset="{{ asset('assets/img/errors/compass.svg') }}">
            </section>    
@endsection
