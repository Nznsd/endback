@extends('layouts.error')

@section('title', '403 - Forbidden')

@section('content')
            <p class="header">Forbidden</p>
            <p class="text">We advice you go back to safety, we mean <a href="javascript:window.history.go(-1)">the previous page</a> 😇 </p>
            <section class="num">
                <span>403</span><img class="error-403" src="{{ asset('assets/img/errors/hold.png') }}" alt="hold" srcset="{{ asset('assets/img/errors/hold.svg') }}">
            </section>
@endsection
