<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/bootstrap.css') }}" />
       <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/index.css') }}" />
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/ico/mynti-logo.ico') }}" />
        <title>NTI</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,200,300" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/favicon.ico') }}" type="image/x-icon" rel="icon">
        
        <script type="text/javascript">
            ;(function(w){
                var img = new Image();
                //img.onerror = function(){
                   // img.src = "{{ asset('assets/img/png/mynti-banner.png') }}";
               // };
                img.src = "{{ asset('assets/img/png/mynti-banner.png') }}";

            }(this));
        </script>

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
                                    <img src="{{ asset('assets/img/svg/myntilogo.svg') }}" width="90" height="60" class="pull-left" />
                                </a>
                            </div>
                            <div id="menu" class="collapse navbar-collapse">
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="/" class="active">Home</a></li>
                                    <li><a href="/">NTI Website</a></li>
                                    <li><a href="/">Help &amp; Support</a></li>
                                    <li><a href="/">How to Apply</a></li>
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
                    <h1>Welcome to
                    <br/><b>MyNTI</b>Student
                    <br/>Portal</h1>
                    <p>Please note that you will not be allowed to matriculate and <br>
                    therefore become a bona fide student if you do<br> 
                    not complete all the stipulated registration processes.</p>
                    <a href="{{ env('APP_URL') . '/applicants'}}" class="btn btn-success">New Students Apply</a> <a href="/" class="btn btn-primary">Current Students Register</a>

                </div>
            </div>
        </div>
        <div class="content">
            <div class="container">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Student Application &amp; Registration Guide</h4>
                    <p class="midcontent">The National Teachers' Institute was is s single mode a distance education Institution dedicated to teacher training. It was established in 1976 by the Federal Government</p>
               
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Information for Returning Students</h4>
                    <p class="midcontent">The National Teachers' Institute was is s single mode a distance education Institution dedicated to teacher training. It was established in 1976 by the Federal Government</p>
               
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4>Important Dates &amp; School Calendar</h4>
                    <p class="midcontent">The National Teachers' Institute was is s single mode a distance education Institution dedicated to teacher training. It was established in 1976 by the Federal Government</p>
                    
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
                <p>Copyright &copy; 2016 National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="-1">Omniswift <b class="">|</b></a> <a href="/" tabindex="-1">Terms of Use <b class="">|</b></a> <a href="/" tabindex="-1">Privacy Policy</a></p>
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
