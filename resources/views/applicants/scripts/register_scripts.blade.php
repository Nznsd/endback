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
                <body onload='javascript: this.onmessage = function(d){ var _fdata = d.data, n = "", el = null, i = 0, form = this.document.forms["regdata"], elems = form.elements; this.parent.console.log("@@@@@", elems); for(; i < elems.length; i++){ el = elems[i]; n = el.name; el.value = _fdata[n]; } this.parent.console.log(d.url, ";;;;;", d.method , ";;;;;", JSON.stringify(_fdata)); form.action = d.url; form.method = d.method; form.submit(); }; this.document.body.removeAttribute("onload");'>
                    <form method="post" name="regdata" target="_parent">
                        <input type="hidden" name="firstname" value="">
                        <input type="hidden" name="middlename" value="">
                        <input type="hidden" name="lastname" value="">
                        <input type="hidden" name="email" value="">
                        <input type="hidden" name="phone" value="">
                        <input type="hidden" name="gender" value="">
                        <input type="hidden" name="maritalstatus" value="">
                        <input type="hidden" name="birthdate" value="">
                        <input type="hidden" name="password_try" value="">
                        <input type="hidden" name="password_confirm" value="">
                        <input type="hidden" name="_token" value="">
                    </form> 
                </body>
            </html>
        </script>
        
        <iframe id="submit_sink" name="submit_sink" class="absolute box-off" src="about:blank" marginheight="0" marginwidth="0" frameborder="0"></iframe>
