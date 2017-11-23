@extends('students.layouts.app')

@section('banner-tab')
    <ul class="list-inline mynti-tabs">
        <li class="{{ @$tab1 }}"><a href="{{ url('/students/fees') }}" class="text-titlecase mynti-tab-item mynti-box">Outstanding Fees</a></li>
        <li class="{{ @$tab2 }}"><a href="{{ url('/students/fees/history') }}" class="text-titlecase mynti-tab-item mynti-box">Payment History</a></li>
        <li class="{{ @$tab3 }}"><a href="{{ url('/students/fees/others') }}" class="text-titlecase mynti-tab-item mynti-box">Other Fees</a></li>
        <li class="active"><a href="{{ url('/students/fees/others') }}" class="text-titlecase mynti-tab-item mynti-box">Verify Payment</a></li>

    </ul>  
@endsection

@section('filter')
    <form action="{{ url('/students/payment/status') }}" method="GET" class="form-inline">
        <input type="text" name="RRR" class="form-control text-field" style="color: #555; font-weight: bold; padding: 2px; font-size: 13px" autofocus placeholder="Enter RRR" value="{{ $RRR }}" required/>
        <button style="padding: 3px;">Verify RRR</button>
    </form>
    
@endsection

@section('content')
    <div class="table-responsive">

        <div style="background-color: #FBFCFD; text-align: center; padding: 30px;">

        @if($status == "00" || $status == "01")
                        <p><img src="{{ asset('assets/img/png/approved.png') }}" alt="Approved" width="200"/></p>
                        <h2 style="color: #47AD2B; margin-top: 10px;"><b>{{ $status }}: {{ strtoupper($message) }}</b></h2>    
                        <p style="margin-top: 5px;"><a href="{{ url('students/courses') }}">Proceed to Register</a></p>                  
        @elseif($status == "021" || $status == "025")
                        <p><img src="{{ asset('assets/img/png/pending.png') }}" alt="Pending" width="200"/></p>
                        <h2 style="color: #2A7ABF; margin-top: 10px;"><b>{{ $status }}: {{ strtoupper($message) }}</b></h2>                                    

        @else
                        <p><img src="{{ asset('assets/img/png/disapproved.png') }}" alt="Approved" width="200"/></p>
                        <h2 style="color: #CC3333; margin-top: 10px;"><b>{{ $status }}: {{ strtoupper($message) }}</b></h2>   

        @endif
      
        </div>
  
    </div>            
@endsection
