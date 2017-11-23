@extends('layouts.error')

@section('title', 'Help is on the way!')

@section('content')
            <p class="header">{{ @$message }}</p>
            <p class="text">The error below has been sent to <a href="mailTo: support@omniswift.com">support@omniswift.com</a>. It will be resolved within 24 hours. </p>
               <div style="background-color: #fafafa; padding: 15px; width: 600px; margin: 10px auto; color: red" >
                    <code>{{ @$error }}</code>
                </div>     

                <p><a href="javascript:window.history.go(-1)" class="btn">Try again</a></p>           
@endsection
