<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Your Application</title>
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


<body>
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
            <div class="panel-heading" align="center" style="margin-left: 135px;">
              <p>NATIONAL TEACHERS' INSTITUTE, KADUNA</p>
              <p>P.M.B T2191</p>
              <P>KADUNA NIGERIA</P>
              <p><i>(ACADEMIC RECORDS DIVISION)</i></p>
              <p><u>APPLICATION FOR ADMISSION INTO THE {{ $data['programme']->abbr }} PROGRAMME FOR THE {{ $data['academicSessionInfo']->academicSession }} SESSION</u></p>

            </div>
            

          
        </div>
        <!-- /.col -->
    </div>

        <div class="panel-body">

            <div class="row">

                        

                <table class="table">

                    <tr>
                        <td>SURNAME</td>
                        <td>OTHER NAMES (In full)</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->surname }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->firstname }} {{ $applicant->othername }}" class="form-control"></td>
                    </tr>
                    
                </table>


                <table class="table">

                    <tr>

                        <td>DATE OF BIRTH</td>
                        <td>GENDER</td>
                        <td>MARITAL STATUS</td>

                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->dob }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->gender }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->marital_status }}" class="form-control"></td>
                    </tr>
                    
                </table>


                <table class="table">

                    <tr>
                        <td>STATE OF ORIGIN</td>
                        <td>LOCAL GOVT OF ORIGIN</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $data['soo']->name }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $data['soo_lga']->name }}" class="form-control"></td>
                    </tr>
                    
                </table>

                <table class="table">

                    <tr>
                        <td>STATE OF RESIDENCE</td>
                        <td>LOCAL GOVT OF RESIDENCE</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $data['sor']->name }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $data['sor_lga']->name }}" class="form-control"></td>
                    </tr>
                    
                </table>


                <table class="table">

                    <tr>
                        <td>TELEPHONE NUMBER</td>
                        <td>EMAIL ADDRESS</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->phone }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $applicant->email }}" class="form-control"></td>
                    </tr>
                    
                </table>


                <table class="table">

                    <tr>
                        <td>STUDY CENTRE</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $data['study_center']->name }}" class="form-control"></td>
                    </tr>
                    
                </table>


                <table class="table">

                    <tr>
                        <td>CHOICE OF PROGRAMME</td>
                        <td>FIRST CHOICE</td>
                        <td>SECOND CHOICE</td>
                    </tr>

                    <tr>
                        <td><input type="text" name="" disabled="" value="{{ $data['programme']->name }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $data['first_choice']->name }}" class="form-control"></td>
                        <td><input type="text" name="" disabled="" value="{{ $data['second_choice']->name }}" class="form-control"></td>
                    </tr>
                    
                </table>

                <p><b>SECTION B - EDUCATIONAL BACKGROUND</b></p>
                
                @if(isset($tertiary))
                    <p><i>8. TERTIARY</i></p>
                    <table class="table table-bordered">

                        <tr>
                            <th>S/N</th>
                            <th>INSTITUTION</th>
                            <th>YEAR OF GRADUATION</th>
                            <th>TYPE OF QUALIFICATION</th>
                            <th>GRADE(S) IN TEACHING SUBJECT</th>
                        </tr>
                        @foreach($tertiary as $edu)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ json_decode($edu->school)->name }}</td>
                                <td>{{ json_decode($edu->school)->year }}</td>
                                <td>{{ json_decode($edu->school)->type }}</td>
                                @foreach(json_decode($edu->grades) as $key => $value)
                                <td>{{ $grades[intval($value) - 1]->name }}</td>
                                @endforeach
                            </tr>
                        @endforeach

                    </table>
                @endif

                @if(isset($o_level))
                    <p><i>9. O LEVELS</i></p>

                    <table class="table table-bordered">
                        <tr>
                            <th colspan="4">SSCE/GCE/OTHER</th>
                            
                        </tr>

                        <tr>
                            <td>S/N</td>
                            <td><i>Subject</i></td>
                            
                            <td><i>Grade</i></td>
                            <td><i>Year</i></td>
                            
                        </tr>

                        @foreach($o_level as $edu)
                            @foreach(json_decode($edu->grades) as $key => $value)
                                <tr>
                                    <td>{{ $loop->parent->index * 9 + $loop->iteration}} 
                                    </td>
                                    <td>{{ $subjects[intval($key) - 1]->name }}</td>
                                    <td>{{ $grades[intval($value) - 1]->name }}</td>
                                    <td>{{ $loop->first ? json_decode($edu->school)->year : '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        
                    </table>

                @endif

                @if(isset($work_experience))
                    <p>10. WORKING EXPERIENCE</p>
                    <table class="table table-bordered">
                        <tr>
                            <th>S/N</th>
                            <th>EMPLOYER</th>
                            <th>POSITION/RANK</th>
                            <th>DATES</th>
                            <th>BRIEF JOB DESCRIPTION</th>
                        </tr>

                        <tr>
                            <td>1</td>
                            <td>{{ $work_experience->employer }}</td>
                            <td>{{ $work_experience->position }}</td>
                            <td>{{ $work_experience->startDate }} to {{ $work_experience->endDate }}</td>
                            <td>{{ $work_experience->desc }}</td>
                        </tr>

                    </table>
                @endif

                <p>CONTACT ADDRESS: <input type="text" name="" disabled="" value="{{ $applicant->address }}" class="form-control"></p>
              
            </div>

        </div>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
  <!-- /.content-wrapper -->


        
</div>

</body>

</html>
      