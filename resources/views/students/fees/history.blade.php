@extends('students.layouts.app')

@section('banner-tab')
    @include('students.fees.bannerTemplate')
@endsection

@section('filter')
    <form action="{{ url('/students/fees/verifyPayment') }}" method="POST" class="form-inline">
        <label>Academic Session:</label>
        <select name='paymentHistoryFilter' class="form-control">
            {{ $studentInstance->printMyLevelDropdown() }}
        </select>
        <span id="info"></span>
    </form>    
@endsection

@section('content')
            <div class="table-responsive" id="historyTable">
                {{ $studentInstance->printPaymentHistroyTable() }}
            </div>            
@endsection
