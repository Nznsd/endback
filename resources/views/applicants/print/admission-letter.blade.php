<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admission Letter</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/skins/_all-skins.min.css">

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

  
  
  <style>
  *
  {
      font-family: 'Quicksand', sans-serif !important;
      /*font-family: 'UnifrakturCook', cursive;*/

  }




  </style>
</head>



<!-- Content Wrapper. Contains page content -->
  <div class="container">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="invoice panel panel-default">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <!-- <section class="content-header"> -->
          <!-- <div class="panel panel-default"> -->
            <div class="panel-heading" align="center" style="margin-left: 300px;">
              <p>NATIONAL TEACHERS' INSTITUTE, KADUNA</p>
              <p>KM 5 KADUNA - ZARIA   EXPRESS WAY,  PMB 2191, KADUNA,  NIGERIA</p>
              <P>ACADEMIC   RECORDS   DIVISION</P>
              <p>{{ $data['programme']->abbr }} PROGRAMME ADMISSION {{ $data['academicSessionInfo']->academicSession }}</p>

            </div>
            
              <h5>Ref No: NTI/REG./ARD/ADM/17</h5>

          <!-- </div> -->
          <!-- </section> -->
          
        </div>
        <!-- /.col -->
      </div>

      <div class="panel-body">

      <div class="row">

        <div class="col-md-6">

        <!-- Applicant's photograph goes here -->
        @if(isset($passport))
           <img src="{{ $passport->src }}" height="300" width="300" class="img-rsponsive"> 
        @endif
        </div>

        <div class="mynti-admission-letter-info col-md-6">

          <ul class="list-group">

            <li class="list-group-item"><b>State of residence</b>: {{ $data['sor']->name }}</li>
            <li class="list-group-item"><b>Study center</b>: {{ $data['study_center']->name }}</li>
            <li class="list-group-item"><b>Course of study</b>: {{ $data['first_choice']->name }}</li>
            <li class="list-group-item"><b>Entry Level</b>: {{ $applicant->entry_level }}</li>
            <li class="list-group-item"><b>Entry Type</b>: {{ $applicant->entry_type }}</li>
            <li class="list-group-item"><b>Duration</b>: {{ $data['programme']->min_duration }} year(s)</li>
            <li class="list-group-item"><b>Phone Number</b>: {{ $applicant->phone }}</li>
            <li class="list-group-item"><b>RRR</b>: {{ json_decode($transaction_tuition->remita_after)->RRR }}</li>
            
          </ul>
          
        </div>
          
        </div>

      
      <br>
      <p>Dear <b>{{ $applicant->surname }} {{ $applicant->firstname }} {{ $applicant->othername }}</b></p>
      <br>

      <h4 align="center"><u>OFFER OF PROVISIONAL ADMISSION INTO NTI {{ $data['programme']->abbr }} PROGRAMME</u></h4>
      <br>

      <div>
        <p>I am pleased to inform you that you have been offered Provisional Admission into the NTI {{ $data['programme']->name }} programme. 
        The duration of the programme is {{ $data['programme']->min_duration }} year(s). This offer is subject to the following conditions: </p>

        <p>1. That the information provided in your Application Form is correct in every respect;</p>
        <p>2. That you will tender all the originals of your credentials for PHYSICAL inspection at your State NTI Office;</p>
        <p>That you would be withdrawn from the Programme at any time it is discovered that the Credentials you presented at Registration were fake or would not have qualified you for admission;</p>
        <p>That you will pay ₦{{ $transaction_tuition->amount }} only being your Tuition Fees at the beginning of each Semester.</p>

        <br>
        <p>Your Registration Number will be provided to you at the time of Registration. If you accept this offer, please sign below. </p>
        <br>
        <br>

        SIGN ......................................................................DATE......................................................... 

        <p>Please accept my congratulations.</p>

        <!-- Signature photo goes here -->
        <!-- <img src=""> -->

        <p><b>F.A Jega (Mrs.)</b></p>
        <p><b>For: Director General &amp; Chief Executive</b></p>
      </div>

      

      
    </div>

    </div>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- /.content-wrapper -->


        
      </div>


      