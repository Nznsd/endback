@extends('layouts.error')

@section('title', '413 - Request Entity too large')

@section('content')
            <p class="header">HTTP Error 413 Request Entity too large</p>
            <p class="text">We advice you go back to safety, we mean <a href="javascript:window.history.go(-1)">the previous page</a> 😇 </p>
            <section class="num">
                <span>413</span><img class="error-413" src="{{ asset('assets/img/errors/siren.png') }}" alt="siren" srcset="{{ asset('assets/img/errors/siren.svg') }}">
            </section>    
@endsection
