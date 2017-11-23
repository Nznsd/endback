<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
    
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/bootstrap.css') }}" />

        <!-- HTTP/2 Server-Push Prerender for Register and Login pages ONLY -->
        <link rel="prerender" href="{{ env('APP_URL') . '/applicants/apply' }}">
        <link rel="prerender" href="{{ env('APP_URL') . '/applicants/login' }}">
       
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/index.css') }}" />
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/ico/mynti-logo.ico') }}" />
        <title>NTI</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/favicon.ico') }}" type="image/x-icon" rel="icon">

        <script src="//code.tidio.co/nc70wr39qahbhlfaifpbvg9yfrssipkz.js"></script>
        
        <script type="text/javascript">
            ;(function(w){
                var img = new Image();
                //img.onerror = function(){
                   // this.src = "{{ asset('assets/img/png/mynti-banner.png') }}";
               // };
                img.src = "{{ asset('assets/img/png/mynti-banner.png') }}";

            }(this));
        </script>
        <!-- Hotjar Tracking Code for mynti.omniswift.com 
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:658531,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>  -->

    </head>

    <body>
        <div id="header" class="navbar-fixed">
            <div id="header-nav">
                <div class="container">
                    <nav class="navbar">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" data-toggle="collapse" data-target="#menu" aria-expanded="false" class="navbar-toggle collapsed">
                                    <span id="nav-menu">MENU</span>
                                </button>
                                <a href="#" class="navbar-brand">
                                    <img src="{{ asset('assets/img/png/myntilogo.png') }}" srcset="{{ asset('assets/img/svg/myntilogo.svg') }}" style="margin-top:-22px;"  width="90" height="60" class="pull-left" />
                                </a>
                            </div>
                            <div id="menu" class="collapse navbar-collapse">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="/" class="active">Home</a></li>
                                    <li><a href="/application-guide">How to Apply</a></li>
                                    <li><a href="/registration-guide/1.1/en/topic/starting-your-registration-returning-students">How to Register</a></li>
                                    <li><a href="/support">Help &amp; Support</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="banner">
            <img src="{{ asset('assets/img/png/mynti-banner.png') }}" class="img-responsive">
            <div class="container">
                <div class="jumbotron">
                    <h1>Welcome to the upgraded <br> NTI Students Portal</h1>
                    <p>The students portal has undergone some major upgrades! <br> This new upgrade aims to simplify the new students <br> application &amp; current students registration processes.</p>
                    <a href="{{ env('APP_URL') . '/applicants/apply'}}" class="btn btn-success">New Students Apply</a> <a href="{{ url('/students/create') }}" class="btn btn-primary">Current Students Register</a>

                </div>
            </div>
        </div>
        <div class="content">
            <div class="container">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>New Student Application Guide</h4>
                    <p class="midcontent">Please click on the link below to view the recently updated NTI portal, new students application guide <a href="{{ env('APP_URL') . '/application-guide' }}">Click Here</a></p>
               
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Current Students Registration Guide</h4>
                    <p class="midcontent">Please click on the link below to view the updated NTI portal, current &amp; returning students registration guide. <a href="{{ env('APP_URL') . '/registration-guide' }}">Click Here</a></p>
               
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Important Dates &amp; School Calendar</h4>
                    <p class="midcontent">For information regarding student application and registration deadlines and other important dates. <a href="https://nti.edu.ng">Click Here</a></p>
                    
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Contact myNTI Support</h4>
                    <p class="midcontent"><p>Please reach us via our contact center on
                    <br/><b>0700 CALL MYNTI (0700 2255 69684)</b>
                    <br/>SMS :09064579779
                    <br/>Whatsapp:09097807503
                    <br/>Twitter <b>@ntiedung</b>
                    <br/>Facebook <b>@ntiedung</b></p>
                    
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="container">
                <p>Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="http://www.omniswift.com" tabindex="-1">Omniswift <b class="">|</b></a> <a href="/" tabindex="-1">Terms of Use <b class="">|</b></a> <a href="/" tabindex="-1">Privacy Policy</a></p>
            </div>
        </div>

        <script type="text/javascript">
            ;(function(w, d){

                var position = 0,
                    callback = function(e){

                        var topScrollOffset = (w.pageYOffset || d.documentElement.scrollTop || d.body.scrollTop),
                            header = d.getElementById('header'),
                            classTitle = " inverted"; 


                        if(position <= topScrollOffset){
                            if(!(header.className.indexOf(classTitle) + 1)){
                                if(position > 5 && position >= 130){
                                    header.className += classTitle;
                                }
                            }
                        }else{
                            if((header.className.indexOf(classTitle) + 1)){
                                if(position == 0 || position <= 129){
                                    header.className = header.className.replace(classTitle, '');
                                }
                            }
                        }

                        position = topScrollOffset;

                    };

                if('attachEvent' in w){
                    w.attachEvent('scroll', callback);
                }else if('addEventListener' in w){
                    w.addEventListener('scroll', callback, false);
                }

                if('dispatchEvent' in w){
                    w.dispatchEvent(new CustomEvent("scroll"));
                }else if('fireEvent' in w){
                    w.fireEvent(d.createEventObject("scroll"));
                }

            }(this, this.document));
        </script>
    </body>

</html>
