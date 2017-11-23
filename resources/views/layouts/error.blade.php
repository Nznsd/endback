<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <meta name="author" content="OmniSwift">

        <title>@yield('title')</title>

        <link rel="shortcut icon" href="{{ asset('assets/img/errors/logo.svg') }}" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('assets/css/errors/style.css') }}">
    </head>
    <body>
        
        <!-- HEADER -->

        <header class="main-header">
            <nav>
                <ul>
                    <li>
                        <a href="#">
                            <img src="{{ asset('assets/img/errors/MyNTI Logo.png') }}" alt="MyNTI Logo" srcset="{{ asset('assets/img/errors/MyNTI Logo.svg') }}">
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <!-- ERROR CONTENTS -->
        
        <section class="error-content">
            @yield('content')
        </section>

    </body>
</html>
