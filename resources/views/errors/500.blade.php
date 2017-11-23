@extends('layouts.error')

@section('title', '500 - Internal Server Error')

@section('content')
            <p class="header">Internal Server Error</p>
            <p class="text">We advice you go back to safety, we mean <a href="javascript:window.history.go(-1)">the previous page</a> 😇 </p>
            <section class="num">
                <span class="s-500">500</span><img class="error-500" src="{{ asset('assets/img/errors/broken-link.png') }}" alt="broken-link" srcset="{{ asset('assets/img/errors/broken-link.svg') }}">
            </section>    
@endsection

