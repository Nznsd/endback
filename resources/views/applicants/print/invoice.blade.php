

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/skins/_all-skins.min.css">
  <!-- fullCalendar 2.2.5-->
  <!--<link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.print.css" media="print">-->
  <!-- iCheck -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-morris/1.3.0/angular-morris.min.js">
  <!-- jvectormap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.4/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.5.3/datepicker.min.js">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <!--<link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">-->
  
  <style>
  body,h1,h2,h3,a,b
        {
            font-family: 'Quicksand', sans-serif;
            /*font-family: 'UnifrakturCook', cursive;*/

        }
        </style>
</head>



<!-- Content Wrapper. Contains page content -->
  <div class="container">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice
        <small></small>
      </h1>
      
    </section>


    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> NTI <hr>
            <small class="pull-right">Date: {{ \Carbon\Carbon::now() }}</small>
            <br>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>National Teachers Institute.</strong><br>
            Kaduna-Zaria Express Way<br>
            Rigacikun, Rigachikun, Nigeria<br>
            Phone: +234 807 748 6868<br>
            Email: info@nti.edu.ng
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong>{{ $applicant->firstname }} {{ $applicant->surname }}</strong><br>
            
            Phone: {{ $applicant->phone}}<br>
            Email: {{ $applicant->email}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice {{ $applicant->app_no }}</b><br>
          <b>Order ID:</b> {{ $transaction->order_id }}<br>
          <b>RRR: </b>{{ json_decode($transaction->remita_before)->RRR }}
          
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
      <hr>

      <h2 align="center">Order Summary</h2>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Payment Name</th>
              
              <th>Amount</th>
              
            </tr>
            </thead>
            <tbody>
              @foreach($collection as $key => $value)
                <tr>
                  <td>{{ strtoupper($key) }}</td>
                  
                  <td>{{ $value }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-md-8 col-xs-8">
          <p class="lead">Payment Methods:</p>
          <img src="{{ asset('assets/img/png/remita.png') }}" alt="Visa">
          
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-xs-4" style="">
          <p class="lead">Amount Due:</p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{ $transaction->amount}}</td>
              </tr>
              <tr>
                <th>Tax</th>
                <td>0.00</td>
              </tr>
              
              <tr>
                <th>Total:</th>
                <td>{{ $transaction->amount}}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- /.content-wrapper -->

