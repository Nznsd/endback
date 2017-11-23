<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/bootstrap.css') }}" />

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- HTTP/2 Server-Push Prerender for Register and Login pages ONLY -->
        <link rel="prerender" href="{{ env('APP_URL') . '/applicants/apply' }}">
        <link rel="prerender" href="{{ env('APP_URL') . '/applicants/login' }}">

        <link rel="dns-prefetch" href="https://cdn.trackjs.com">
        <link rel="dns-prefetch" href="https://code.tidio.co">

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

    <!-- BEGIN TRACKJS -->
      <script type="text/javascript">window._trackJs = { token: 'b9bb41a5f4b0473e86878d6260528fce' };</script>
      <script type="text/javascript" src="https://cdn.trackjs.com/releases/current/tracker.js"></script>
    <!-- END TRACKJS -->

    </head>

    <body>
        <div id="header" class="navbar-fixed" style="background-color:#e2e2e2;z-index:300;">
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
                                    <li><a href="/">Home</a></li>
                                    <li><a href="/application-guide">How to Apply</a></li>
                                    <li><a href="/registration-guide/1.1/en/topic/starting-your-registration-returning-students">How to Register</a></li>
                                    <li><a href="/support" class="active">Help &amp; Support</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="pane">
            <div class="container" style="margin-top:80px;">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <section class="pane">
                                <h1 class="pane-heading">Help &amp; Support <a href="javascript:window.history.go(-1);" class="btn btn-default">Go Back</a></h1>
                                <div class="panel panel-default">
                                    <h3 class="panel-sub-heading hr">Online Guide Pages</h3>
                                    <div class="panel-body">Our Online guides can help you get up to speed with all you need to know about how to use the NTI Portal. It is organized as a manula and has screenshots and tips to aid quick understaning.</div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-right">
                                            <a href="/application-guide" class="btn btn-primary">Guide for Applications</a>
                                            <a href="/registration-guide" class="btn btn-default">Guide for Registration</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <h3 class="panel-sub-heading hr">Live Support Chat</h3>
                                    <div class="panel-body">Our Support chat is open from Mondays to Saturdays (9:00 AM - 5:00 PM). If you have any on-site or off-site questions on student registrations, payments, form downloads and/or submissions, please reach out using the widget at the <b class="text-primary">bottom right corner of this page</b>.</div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-right">
                                            <a href="javascript:openUpChat();" class="btn btn-primary">Reach out Now</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <h3 class="panel-sub-heading hr">Frequently Asked Questions</h3>
                                    <div class="panel-body">Our support team can only attend to so much at any time especially when they are overwhelmed. Therefore, we strongly encourage you to review our <b class=""> common questions and answer list</b> from time to time.
                                    </div>
                                    <div class="panel-footer clearfix">
                                        <div class="pull-right">
                                            <a class="btn btn-primary" data-toggle="modal" data-target="#modalFAQs">View FAQs</a>
                                        </div>
                                    </div>
                                </div>

                            </section>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                         <section class="pane">
                                <div class="well">
                                    <h4 class="well-heading">Suggestion Box</h4>
                                    <p>
                                        You can also help us out by suggesting how we can make NTI Portal better
                                    </p>
                                    <div class="">
                                        Reach us on <b class="text-primary">WhatsApp</b> 09097807503 (Chats Only)
                                    </div>
                                </div>
                         </section>
                         <section class="pane" style="margin-top:18px;">
                             <div class="well">
                                <h4 class="well-heading">NTI Payment Evidence</h4>
                                <p>
                                    If you paid directly on Remita or you paid on the old NTI system (v1), please click the button below to send in your details 
                                </p>
                                <div class="">
                                   <a class="btn btn-primary" data-toggle="modal" data-target="#modalTransit">Submit Evidence Details Now</a>
                                </div>
                            </div>
                         </section>
                    </div>
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
                <p>Copyright &copy; {{ date('Y') }} National Teachers Institute. All rights reserved. Powered by <a href="https://www.omniswift.com" tabindex="-1">Omniswift <b class="">|</b></a> <a href="/" tabindex="-1">Terms of Use <b class="">|</b></a> <a href="/" tabindex="-1">Privacy Policy</a></p>
            </div>
        </div>

            <!-- Modals -->
<div class="modal fade" id="modalTransit" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    NTI v1 Payment Evidence Form
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <form class="form-horizontal" role="form" name="nti-form" target="submit_sink" enctype="multipart/form-data" action="https://my.nti.edu.ng/back-support">
                     <div class="form-group">
                    <label class="col-sm-2 control-label"
                              for="f_name">Full Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                        id="f_name" name="f_name" placeholder="Enter Full Name"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"
                              for="s_centre">Study Centre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                        id="s_centre" name="s_centre" placeholder="Enter Study Centre"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"
                          for="a_paid" >Amount Paid</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="a_paid" name="a_paid" placeholder="Enter Amount Paid"/>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-sm-2 control-label"
                          for="pay_files" >Attach Evidence of Payment (Invoice)</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control"
                            id="pay_files" name="i_file" />
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-sm-2 control-label"
                          for="pay_files" >Attach Evidence of Payment (Receipt)</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control"
                            id="pay_files" name="r_file" />
                    </div>
                  </div>
                  <div class="form-group">
                    
                        <label class="col-sm-2 control-label"
                              for="old_rrr">
                             Version 1 RRR
                        </label>
                         <div class="col-sm-10">
                            <input type="text" class="form-control"
                                id="old_rrr" name="old_rrr" placeholder="Enter Your Old RRR"/>
                        </div>
                  
                  </div>
                  <div class="form-group">
                    
                        <label class="col-sm-2 control-label"
                              for="new_rrr">
                             Version 2 RRR
                        </label>
                         <div class="col-sm-10">
                        <input type="text" class="form-control"
                            id="new_rrr" name="new_rrr" placeholder="Enter Your New RRR"/>
                        </div>
                    
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success">SEND</button>
                    </div>
                  </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalFAQs" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Frequestly Asked Questions
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="height:450px;overflow:auto;">

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why can't i verify my RRR when i pay directly on Remita ?
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                      If you made your payments directly on REMITA please contact our
                      support team (via chat only) with all evidence of payments so
                      you can have it validated and regularized on the NTI system. Also,
                      please note that if you did make your payments through the new <b>NTI
                      Portal (V2)</b>, you can verify your <b>RRR</b> without contacting
                      support.
                </div>
            </div>
        </div>



        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why does my Student Dashboard show a different semester than the current one ?
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    This will never happen with this new version of <b>NTI V2</b> as it is an old issue with
                    the new one.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why can't i generate an RRR for my Payments as an Applicant or Student ?
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    This will never happen with this new version of <b>NTI V2</b> as it is an old issue with
                    the new one.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why can't i verify my RRR which i generated from the old NTI system (v1) ?
                    </a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                      Please send an email to <a href="mailto:support@omniswift.com">support@omniswift.com</a>.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why can't i find my surname when i search through on the new NTI system ?
                    </a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                      Please send an email to <a href="mailto:support@omniswift.com">support@omniswift.com</a>.
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                        <i class="more-less glyphicon glyphicon-plus">+</i>
                        Why can't i find my fullname on the new NTI system as a 2017 intake ?
                    </a>
                </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                      Please send an email to <a href="mailto:support@omniswift.com">support@omniswift.com</a>.
                </div>
            </div>
        </div>

    </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
            </div>
        </div>
    </div>
</div>

<iframe src="javascript:false" name="submit_sink" id="submit_sink" allowtransparency="no" frameborder="0" scrolling="no" style="position:absolute;z-index:-1;left:-9999px;top:0;"></iframe>

        <script type="text/javascript">
            ;(function(w, d){

                if(typeof d.getElementsByClassName != "function"){
                    d.getElementsByClassName = function(search) {
                        var d = document, elements, pattern, i, results = [];
                        if (d.querySelectorAll) { // IE8
                          return d.querySelectorAll("." + search);
                        }
                        if (d.evaluate) { // IE6, IE7
                          pattern = ".//*[contains(concat(' ', @class, ' '), ' " + search + " ')]";
                          elements = d.evaluate(pattern, d, null, 0, null);
                          while ((i = elements.iterateNext())) {
                            results.push(i);
                          }
                        } else {
                          elements = d.getElementsByTagName("*");
                          pattern = new RegExp("(^|\\s)" + search + "(\\s|$)");
                          for (i = 0; i < elements.length; i++) {
                            if ( pattern.test(elements[i].className) ) {
                              results.push(elements[i]);
                            }
                          }
                        }
                        return results;
                    }
                }

                w.openUpChat = function (){
                    var iframe_win = window.frames[2];
                    var elem = null;

                    try{
                        if(iframe_win && iframe_win.document){
                           elem = iframe_win.document.getElementsByClassName('avatar')[0];
                        }
                    }catch(e){

                    }

                    if(elem !== null){
                        elem.click();
                    }
                };

                if(w.jQuery){
                    $('#modalTransit').on('hidden.bs.modal', function () {
                        d.forms['nti-form'].reset();
                    });

                    $('#modalTransit').on('shown.bs.modal', function () {
  
                    });
                }

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
