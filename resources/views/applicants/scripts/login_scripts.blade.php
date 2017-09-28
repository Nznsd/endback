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
                <body onload='javascript: this.onmessage = function(d){ var _fdata = d.data, n = "", el = null, i = 0, form = this.document.forms["logdata"], elems = form.elements; for(; i < elems.length; i++){ el = elems[i]; n = el.name; if(el.type == "checkbox"){ el.checked = _fdata[n]; }else { el.value = _fdata[n]; } } form.action = d.url; form.method = d.method; form.submit(); }; this.document.body.removeAttribute("onload");'>
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