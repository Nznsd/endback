<!DOCTYPE html>
<html lang="en" class="no-js no-idle-check mynti-app" data-x-path="./offline.html" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xmlns:addthis="http://www.addthis.com/help/api-spec">
    <!--
        Avoid Errors in IE8 for namespaced elements - 
        (Error: Could not set innerHTML: unknown runtime error)

        @see: [http://connect.facebook.net/en_US/all.js] issues
    -->
    <head prefix="og: http://ogp.me/ns# object: http://ogp.me/ns/object#">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- (1) force latest IE rendering engine: bit.ly/1c8EiC9 -->
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        
        <meta name="application-name" content="MyNTI">
        <meta name="application-version" content="2">
        <meta name="application-url" content="https://my.nti.edu.ng/">
        <meta name="google" content="notranslate">

        <!--

            prefetch via HTTP/2 Server-Push to:

            1. Google Analytics
            2. Bugsnag Error Reporting
            3. MixPanel Analytics

            4. Google Fonts (Roboto, Lucida Grande) 

            This in effect will increase page load speed
        -->

        <link rel="dns-prefetch" href="https://cdn.mxpnl.com">
        <link rel="dns-prefetch" href="https://app.bugsnag.com">
        <link rel="dns-prefetch" href="https://www.google-analytics.com">

        <link rel="prefetch" href="https://fonts.googleapis.com/css?family=Roboto">
        <link rel="prefetch" href="https://fonts.googleapis.com/css?family=Lucida+Grande:200,400,600,700,800bold,700italic">

        <!--

            Application-wide support/event settings!

        -->

        <script type="text/javascript">
              // <![CDATA[
              ;(function(w, d){

                /* supported browser versions (engine-based) */

                w.MYNTI_SUPPORTED_TRIDENT_EDGEHTML_VERSION="8.0+";
                w.MYNTI_SUPPORTED_GECKO_VERSION="4.0+";
                w.MYNTI_SUPPORTED_CHROME_WEBKIT_BLINK_VERSION="4.0+";
                w.MYNTI_SUPPORTED_APPLE_WEBKIT_VERSION="4.0+"; 
                w.MYNTI_SUPPORTED_PRESTO_BLINK_VERSION="11.0+";
                w.MYNTI_NO_FLASH_QS="?support_level\x3dno-flash";


                w.APP_NAME="MyNTI";
                w.MYNTI_SERVER_SENT_EVENTS_TOKEN=Math.random((new Date).getTime()).toString(16).replace('.', Math.random((new Date).getTime()).toString(36).substr(2));


                /* Preload spinner image(s) */

                (new Image).src = "{{ asset('assets/img/icons/gif/loader-white.gif') }}"; 
                (new Image).src = "{{ asset('assets/img/icons/gif/loader-grey.gif') }}"; 
                (new Image).src = "{{ asset('assets/img/icons/gif/loader-white-2x.gif') }}"; 
                (new Image).src = "{{ asset('assets/img/icons/gif/loader-grey-2x.gif') }}"; 
                
            }(this, this.document));
              // ]]-->
        </script>

        <title>NTI Portal | Returning Students </title>

        <meta name="_token" content="cPKJI9QcWayr2wjnQEVP5J9FaDgkrERBxFIAj4Asz/fznxVqTxJMq/Wr/iSNZbf/5cntn91+a0g9/nzFlumSK">

        <meta name="googlebot" content="nofollow, noindex">
        <meta name="robots" content="noodp">
        <meta name="author" content="OmniSwift">
        <meta name="developer" content="Isocroft, Sadiq Lukman">
        <meta name="copyright" content="OmniSwift">
        <meta name="generator" content="_custom">
  
        <!--

            Copyright (c) 2017 by OmniSwift (https://www.omniswift.com)

            Permission is hereby held in private to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software with restriction, including the rights not to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and not to permit persons to whom the Software is furnished to do so, subject to the following conditions:

            The above copyright notice  shall be included in all copies or substantial portions of the Software.
        
        -->

        <!-- 

        IE 9 (and above) support:

        1. Media Queries
        2. Advanced CSS3 Selectors + Pseudo Classes
        3. All HTML5 elements and many more.

            So, no need to Shim the above...

        IE 9 (only) doesn't support these in CSS:

        1. text-shadow
        2. box-shadow
        3. 2d-transform - CSSSandpaper (Zoltan Hawrylurk)
        4. gradients

            So, need to be Shimmed!


    -->


    <!--[if gte IE 9]>
        <script type="text/javascript" async="async" defer="defer" src="./assets/js/oldie/PIE/CSS3Pie_IE9.min.js"></script>
        <script type="text/javascript" async="async" defer="defer" src="//ajax.googleapis.com/libs/swfobject/2.2/swfobject.js"></script>
    <![endif]-->


    <!-- Old IE Stuff - / Selectivizr (v1.0.3b-extended) / NWMatcher (v1.2.5) / HTML5Shiv (v3.7.3) / Respond.js (v1.5.0) / CSS3PIE {IE7/8/9} (v2.0.0) / BoxSizing (v0.0.1) -->

    <!-- 

        @see: https://github.com/keithclark/selectivizr/issues/23

        1. Load Selectivizr.js before Respond.js
        2. Ensure you don't include css file(s) using @import (Respond.js will not load it)
        3. Ensure that all CSS files are loaded before Selectivizr
        4. Add jQuery plugin iFixPNG2 (v1.1.0) jquery.ifixpng2.js

    -->
    
    <!--[if (gte IE 6)&(lte IE 8)]> 
        <script type="text/javascript" defer="defer" src="./assets/js/oldie/oldie.min.js"></script>
        <script type="text/javascript" defer="defer" src="//ajax.googleapis.com/libs/swfobject/2.2/swfobject.js"></script>
    <![endif]-->


        <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/lib/sweetalert.css') }}">

        <link type="text/css" rel="stylesheet" media="screen" href="{{ asset('assets/css/lib/animate.min.css') }}">

        <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">


         <!-- Windows-Phone = 70x70 -->
          <meta name="msapplication-TileImage" content="{{ asset('assets/img/tile.png') }}">

          <!-- Apple-iOS - -->
          <meta name="apple-mobile-web-app-status-bar-style" content="blue">

          <!-- Base -->
          <meta name="imagetoolbar" content="no">
          <meta name="msthemecompatible" content="no">
          <meta name="cleartype" content="on">
          <meta name="HandheldFriendly" content="True">
          <!--
  <meta name="MobileOptimized" content="320">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">-->

          <link rel="manifest" type="application/manifest+json" href="{{ asset('manifest.json') }}">

          <!-- Format -->

          <meta name="format-detection" content="telephone=no">
          <meta name="format-detection" content="address=no">

          <!-- Origin Referrer Policy -->
          <meta name="referrer" content="origin-when-cross-origin">

        <!-- FavIcon -->

        <link href="{{ asset('assets/img/icons/favicon.ico') }}" type="image/x-icon" rel="icon">

        <!-- Canonical Address -->

        <link rel="cannoical"  href="https://my.nti.edu.ng" title="">

        <script type="text/javascript" async="async" defer="defer" src="{{ asset('assets/js/shim/es5shim.min.js') }}"></script>
        <script type="text/javascript" async="async" defer="defer" src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script type="text/javascript" async="async" defer="defer" src="{{ asset('assets/js/browsengine.min.js') }}"></script>
        <script type="text/javascript" async="async" defer="defer" src="{{ asset('assets/js/shim/manup.min.js') }}"></script>

        <!--  

            Precaution against Anti-Clickjacking
        -->

        <style id="antiClickjack">body{display:none !important;}</style>
        <script type="text/javascript">
            ;(function(w){
                var doc = w.document, antiClickjack;
               if (w.self === w.top) {
                   antiClickjack = doc.getElementById("antiClickjack");
                   antiClickjack.parentNode.removeChild(antiClickjack);
               } else {
                   w.top.location = w.self.location;
               }
            }(this));   
        </script>

        <!--[if lte IE 9]>
        <script type="text/javascript">
              /*!
                 Shim for the [console] object to avoid IE wahala ('console' object is undefined until you open the IE Dev Tools - conditional definition of console)
               */  

             ;(function(w){
                var method;
                var noop = function(c){ };
                var log = function(){ w.Debug && w.Debug.write.apply(w.Debug, arguments); };
                var methods = [
                  'assert','warn','debug','info','log','dir','clear','exception','group',
                  'groupEnd','timeStamp','trace','table','time','profile','profileEnd','timeEnd'
                ];
                var length = methods.length;
                var console = (w.console || null);
                
                if(console === null){
                    console = {};
                    while(length){
                        length -= 1;
                        method = methods[length];
                        
                        // Only stub undefined routines
                        if( !console[method] ){
                           console[method] = (method === 'log')? log : noop;
                        }
                    }
                    w.console = console;
                }    
            }(this));
        </script>
        <![endif]-->
        <script type="text/javascript">
                ;(function(c){
                        var _ref;
                        if((_ref = c) != null){
                            if(typeof _ref.timeStamp === 'function'){
                                _ref.timeStamp("in html head, about to finish");
                            }
                        }
                }(this.console));
        </script>

        <script async="async" src="//www.google-analytics.com/analytics.js"></script>

        <link type="text/css" rel="stylesheet" media="screen" href="{{ asset('assets/css/lib/bootstrap-daterangepicker.2.1.25.css') }}">

        <link type="text/css" rel="stylesheet" media="screen" href="{{ asset('assets/css/students/students.css') }}"> 
            
        <link rel="stylesheet" media="screen" href="{{ asset('assets/css/lib/bootstrap.css') }}">

        <style>
        </style>
    </head>
    <!--[if IE]> 
<body id="page-top" class="ie-set relative mynti-fullpage unselectable platform-switch-desktop-view not-scrollable" aria-setup-mode="unknown" aria-os-data="unknown" aria-view-mode="unknown" aria-interact-mode="mouse" data-notifications-permissions="unknown">
<![endif]-->
<!--[if !IE]<!--> 
<body id="page-top" class="non-ie relative mynti-fullpage unselectable platform-switch-desktop-view not-scrollable" aria-setup-mode="unknown" aria-os-data="unknown" aria-view-mode="unknown" aria-interact-mode="mouse" data-notifications-permissions="unknown">
<!--<![endif]-->
        
        <header class="mynti-main-header shadow-around clearfix">
            <div class="pull-left mynti-branding" role="branding">
                <span class="mynti-box branding-item">
                    <a href="/" class="mynti-link logo-link" tabindex="0">O</a>
                </span>
            </div>
            <nav class="mynti-desktop-navigation" role="navigation">
                <span class="mynti-box nav-item">
                    <a href="/" class="mynti-link nav-link" tabindex="1">Home</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="/" class="mynti-link nav-link" tabindex="2">NTI Website</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="/" class="mynti-link nav-link" tabindex="3">Help &amp; Support</a>
                </span>
                <span class="mynti-box nav-item">
                    <a href="/" class="mynti-link nav-link" tabindex="4">How to Apply</a>
                </span>
            </nav>
            <p class="pull-right mynti-mobile-navigation" role="button">
                <button class="btn mynti-button-nav-hamburger" tabindex="5">&#9776;</button>
            </p>
        </header>

        <div class="mynti-old-browser-header upgrade-browser-info">
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Internet Explorer</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Google Chrome</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Mozilla Firefox</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
            <p class=""><span class="">You are using an extremely old version of the </span><a href="/" tabindex="-1"><b>Opera</b><img src="" tabindex="-1"> browser so, some features of MyNTI may not work. Please download and install the latest.</a></p>
        </div>

        <main class="mynti-main-section">
            <section class="mynti-login-container">

                @yield('content')

                <footer class="mynti-main-footer">
                    <div class="mynti-copyright-info-container">
                        <span class="mynti-box text-center lighter-size"> Copyright &copy; 2016 National Teachers Institute. All rights reserved. Powered by <a href="http://www.omniswift.com" tabindex="17">Omniswift <b class="">|</b></a> <a href="/" tabindex="9">Terms of Use <b class="">|</b></a> <a href="/" tabindex="10">Privacy Policy</a> </span>
                    </div>
                </footer>
            </section>
        </main>

<!-- Include Date Range Picker -->
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />-->

<!-- Include MomentJS -->
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />-->

        <script id="iframe_document" type="text/mynti-template">
            <!DOCTYPE html>
            <html lang="en" dir="ltr">
                <head>
                    <title></title>
                    <meta charset="utf-8">
                </head>
                <body onload='javascript: this.onmessage = function(d){ var _fdata = d.data, n = "", el = null, i = 0, form = this.document.forms["logdata"], elems = form.elements; for(; i < elems.length; i++){ el = elems[i]; n = el.name; if("checked" in el){ el.checked = _fdata[n]; }else { el.value = _fdata[n]; } } form.action = d.url; form.method = d.method; form.submit(); }; this.document.body.removeAttribute("onload");'>
                    <form method="post" name="logdata" target="_parent">
                        <input type="hidden" name="email" value="">
                        <input type="checkbox" name="remember_me" checked="false">
                        <input type="hidden" name="password" value="">
                        <input type="hidden" name="_token" value="">
                    </form> 
                </body>
            </html>
        </script>
        
        <iframe id="submit_sink" name="submit_sink" class="absolute box-off" src="about:blank" marginheight="0" marginwidth="0" frameborder="0"></iframe>


        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/cdv_js.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/moment-2.18.1.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/sweetalert.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/jquery.combopack.min.js') }}"></script>

        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/radixx.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/lib/lib/idle.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/applicants/mainunit.js') }}"></script>

        <!--<script type="text/javascript" src="{{ asset('assets/js/applicants/login-formunit.js') }}"></script>-->

        <script type="text/javascript" src="{{ asset('assets/js/app-bootstrap.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/students/students.js') }}"></script>

        <script type="text/javascript">
            $('[data-toggle="popover"]').popover()
            .trigger('focus').queue(function(next){
                var $self = $(this);
                setTimeout(function(){
                    $self.popover('destroy');
                }, 4800);
            });  
        </script>

    </body>
</html>